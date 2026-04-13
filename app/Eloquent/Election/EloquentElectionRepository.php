<?php

namespace App\Eloquent\Election;

use App\Domain\Election\ElectionRepository;
use App\Domain\Election\Election;
use App\Domain\Position\Position;
use App\Domain\Candidates\Candidates;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Application\RegisterUser\RegisterUser;

class EloquentElectionRepository implements ElectionRepository
{
    private Database  $db;
    private Reference $electionDB;
    private Reference $electionsDB;
    private Reference $positionsDB;
    private Reference $candidatesDB;
    private RegisterUser $registerUser;

    private const ELECTION_COLLECTION   = 'election_settings';
    private const ELECTIONS_COLLECTION  = 'elections';
    private const POSITIONS_COLLECTION  = 'positions';
    private const CANDIDATES_COLLECTION = 'candidates';

    public function __construct(Database $database, RegisterUser $registerUser)
    {
        $this->db           = $database;
        $this->electionDB   = $this->db->getReference(self::ELECTION_COLLECTION);
        $this->electionsDB  = $this->db->getReference(self::ELECTIONS_COLLECTION);
        $this->positionsDB  = $this->db->getReference(self::POSITIONS_COLLECTION);
        $this->candidatesDB = $this->db->getReference(self::CANDIDATES_COLLECTION);
        $this->registerUser = $registerUser;
    }
    public function getActiveElection(): ?Election
    {
        try {
            $snapshot = $this->electionsDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return null;
            }

            $all = collect($snapshot->getValue())
                ->filter(fn($item) => is_array($item))
                ->map(fn($item) => Election::fromFirebase($item));

            return $all->firstWhere(fn(Election $e) => $e->isActive())
                ?? $all->firstWhere(fn(Election $e) => $e->isUpcoming());
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::getActiveElection — ' . $e->getMessage());
            return null;
        }
    }

    public function updateElectionSchedule(string $electionId, array $data): void
    {
        try {
            $this->db
                ->getReference(self::ELECTIONS_COLLECTION . '/' . $electionId)
                ->update(array_merge(
                    array_intersect_key($data, array_flip([
                        'start_date',
                        'end_date',
                        'opening_time',
                        'closing_time',
                    ])),
                    ['updated_at' => now()->toISOString()]
                ));
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::updateElectionSchedule — ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateElectionGeneral(string $electionId, array $data): void
    {
        try {
            $this->db
                ->getReference(self::ELECTIONS_COLLECTION . '/' . $electionId)
                ->update(array_merge(
                    array_intersect_key($data, array_flip([
                        'election_name',
                        'semester',
                        'academic_year',
                        'description',
                    ])),
                    ['updated_at' => now()->toISOString()]
                ));
        } catch (\Throwable $e) {
            Log::error('EloquentElectionRepository::updateElectionGeneral — ' . $e->getMessage());
            throw $e;
        }
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
            $positionsSnapshot = $this->positionsDB->getSnapshot();

            if (! $positionsSnapshot->exists() || $positionsSnapshot->getValue() === null) {
                return [];
            }

            // 1. Fetch all users keyed by Firebase ID
            $usersById = collect($this->db->getReference('users')->getSnapshot()->getValue() ?? [])
                ->filter(fn($u) => is_array($u) && empty($u['is_deleted']))
                ->keyBy('id');

            // 2. Fetch candidates, join user data, group by position name
            $candidatesGrouped = collect($this->candidatesDB->getSnapshot()->getValue() ?? [])
                ->filter(fn($item) => is_array($item))
                ->map(function ($item) use ($usersById) {
                    $user = $usersById->get($item['user_id'] ?? '');

                    // Attach user data directly into the candidate array
                    $item['full_name']  = $user ? trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) : 'Unknown';
                    $item['course']     = $user['course'] ?? '';
                    $item['year_level'] = $user['year_level'] ?? '';
                    $item['student_id'] = $user['student_id'] ?? '';

                    return $item; // return raw array so we can inspect
                })
                ->groupBy(fn($item) => $item['position'] ?? ''); // ← use raw 'position' key, not getPositionName()

            // 3. Map positions and attach their candidates
            return collect($positionsSnapshot->getValue())
                ->filter(fn($item) => is_array($item))
                ->map(function ($item) use ($candidatesGrouped) {
                    $position = Position::fromFirebase($item);

                    // Match by position name using the raw 'position' field
                    $matched = $candidatesGrouped->get($position->getPositionName(), collect());

                    $position->setCandidates($matched->values()->toArray());

                    return $position;
                })
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

    public function getAllCandidates(): array
    {
        try {
            $activeElection   = $this->getActiveElection();
            $activeElectionId = $activeElection?->getId();
            $usersSnapshot = $this->db->getReference('users')->getSnapshot();
            $usersById = collect($usersSnapshot->exists() ? $usersSnapshot->getValue() : [])
                ->filter(fn($u) => is_array($u) && empty($u['is_deleted']))
                ->keyBy('id');
            $snapshot = $this->candidatesDB->getSnapshot();
            if (!$snapshot->exists() || $snapshot->getValue() === null) {
                return [];
            }
            return collect($snapshot->getValue())
                ->filter(fn($item) => is_array($item))
                ->map(function ($item) use ($usersById) {
                    $user = $usersById->get($item['user_id'] ?? '');
                    if ($user) {
                        $item['full_name']  = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                        $item['course']     = $user['course'] ?? '';
                        $item['year_level'] = $user['year_level'] ?? '';
                        $item['student_id'] = $user['student_id'] ?? '';
                    }
                    return Candidates::fromFirebase($item);
                })
                ->filter(fn(Candidates $c) => $c->getElectionId() === $activeElectionId)
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
