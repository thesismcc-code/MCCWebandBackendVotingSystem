<?php

namespace App\Eloquent\Election;

use App\Domain\Election\ElectionRepository;
use App\Domain\Election\Election;
use App\Domain\Election\Position;
use App\Domain\Candidates\Candidates;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class EloquentElectionRepository implements ElectionRepository
{
    private Database  $db;
    private Reference $electionDB;
    private Reference $positionsDB;
    private Reference $candidatesDB;
    private const ELECTION_COLLECTION   = 'election_settings';
    private const POSITIONS_COLLECTION  = 'positions';
    private const CANDIDATES_COLLECTION = 'candidates';

    public function __construct(Database $database)
    {
        $this->db           = $database;
        $this->electionDB   = $this->db->getReference(self::ELECTION_COLLECTION);
        $this->positionsDB  = $this->db->getReference(self::POSITIONS_COLLECTION);
        $this->candidatesDB = $this->db->getReference(self::CANDIDATES_COLLECTION);
    }

    public function getElection(): ?Election
    {
        try {
            $snapshot = $this->electionDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return null;
            }

            return Election::fromFirebase((array) $snapshot->getValue());
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::getElection — ' . $e->getMessage());
            return null;
        }
    }

    public function saveElection(array $data): void
    {
        try {
            $existing = $this->getElection();

            $this->electionDB->set(array_merge(
                $existing?->toArray() ?? [],
                $data,
                [
                    'id'         => $existing?->getId() ?? Str::uuid()->toString(),
                    'updated_at' => now()->toISOString(),
                    'created_at' => $existing?->getCreatedAt() ?? now()->toISOString(),
                ]
            ));
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::saveElection — ' . $e->getMessage());
            throw $e;
        }
    }
    public function getAllPositions(): array
    {
        try {
            $snapshot = $this->positionsDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return [];
            }

            return collect($snapshot->getValue())
                ->filter(fn($item) => is_array($item))
                ->map(fn($item) => Position::fromFirebase($item))
                ->sortBy(fn(Position $p) => $p->getCreatedAt())
                ->values()
                ->toArray();
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::getAllPositions — ' . $e->getMessage());
            return [];
        }
    }

    public function savePosition(array $data): void
    {
        try {
            $id  = 'pos_' . Str::random(10);
            $now = now()->toISOString();

            $this->db->getReference(self::POSITIONS_COLLECTION . '/' . $id)->set(array_merge($data, [
                'id'         => $id,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::savePosition — ' . $e->getMessage());
            throw $e;
        }
    }

    public function updatePosition(string $id, array $data): void
    {
        try {
            $this->db->getReference(self::POSITIONS_COLLECTION . '/' . $id)->update(
                array_merge($data, ['updated_at' => now()->toISOString()])
            );
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::updatePosition — ' . $e->getMessage());
            throw $e;
        }
    }

    public function deletePosition(string $id): void
    {
        try {
            $this->db->getReference(self::POSITIONS_COLLECTION . '/' . $id)->remove();
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::deletePosition — ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTotalPositions(): int
    {
        return count($this->getAllPositions());
    }

    // ─── CANDIDATES ─────────────────────────────────────────────────────────────

    public function getAllCandidates(): array
    {
        try {
            $snapshot = $this->candidatesDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return [];
            }

            return collect($snapshot->getValue())
                ->filter(fn($item) => is_array($item))
                ->map(fn($item) => Candidates::fromFirebase($item))
                ->sortBy(fn(Candidates $c) => $c->getPositionName())
                ->values()
                ->toArray();
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::getAllCandidates — ' . $e->getMessage());
            return [];
        }
    }

    public function getCandidatesByPosition(string $positionName): array
    {
        return collect($this->getAllCandidates())
            ->filter(fn(Candidates $c) => $c->getPositionName() === $positionName)
            ->values()
            ->toArray();
    }

    public function saveCandidate(array $data): void
    {
        try {
            $id  = 'cand_' . Str::random(10);
            $now = now()->toISOString();

            $this->db->getReference(self::CANDIDATES_COLLECTION . '/' . $id)->set(array_merge($data, [
                'id'         => $id,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::saveCandidate — ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateCandidate(string $id, array $data): void
    {
        try {
            $this->db->getReference(self::CANDIDATES_COLLECTION . '/' . $id)->update(
                array_merge($data, ['updated_at' => now()->toISOString()])
            );
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::updateCandidate — ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteCandidate(string $id): void
    {
        try {
            $this->db->getReference(self::CANDIDATES_COLLECTION . '/' . $id)->remove();
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::deleteCandidate — ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTotalCandidates(): int
    {
        return count($this->getAllCandidates());
    }
}
