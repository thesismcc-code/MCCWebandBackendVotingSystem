<?php

namespace App\Eloquent\Candidates;

use App\Domain\Candidates\Candidates;
use App\Domain\Candidates\CandidatesRepository;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;

class EloquentCandidateRepository implements CandidatesRepository
{
    private Reference $db;
    private Reference $electionsDb;
    private const COLLECTIONS            = 'candidates';
    private const ELECTIONS_COLLECTIONS  = 'elections';

    // Fixed Type: array, not string
    private array|null $activeElectionIdsCache = null;

    public function __construct(Database $db)
    {
        $this->db          = $db->getReference(self::COLLECTIONS);
        $this->electionsDb = $db->getReference(self::ELECTIONS_COLLECTIONS);
    }

    /**
     * Cache active election IDs to avoid repeated queries.
     * Returns array of IDs.
     */
    private function getActiveElectionIds(): array
    {
        // If cache exists, return it immediately (maintains array type)
        if ($this->activeElectionIdsCache !== null) {
            return $this->activeElectionIdsCache;
        }

        // Fetch data from database
        $snapshot = $this->electionsDb
            ->orderByChild('status')
            ->equalTo('active')
            ->getSnapshot();

        $data = $snapshot->exists() && $snapshot->getValue() !== null
            ? $snapshot->getValue()
            : null;

        // Ensure we return an array, even if empty
        return $this->activeElectionIdsCache = array_keys($data ?? []);
    }

    private function toCandidate(string $id, array $data): Candidates
    {
        return Candidates::fromFirebase([
            'id'              => $id,
            'election_id'     => $data['election_id']     ?? '',
            'position_id'     => $data['position_id']     ?? '',
            'party_list_id'   => $data['party_list_id']   ?? '',
            'position_name'   => $data['position_name']   ?? '',
            'full_name'       => $data['full_name']        ?? '',
            'course'          => $data['course']           ?? '',
            'year'            => $data['year']             ?? '',
            'political_party' => $data['political_party']  ?? '',
            'platform_agenda' => $data['platform_agenda']  ?? '',
            'image_url'       => $data['image_url']        ?? '',
            'status'          => $data['status']           ?? 'active',
            'created_at'      => $data['created_at']       ?? '',
            'updated_at'      => $data['updated_at']       ?? '',
        ]);
    }

    public function getCandidates(): array
    {
        $snapshot = $this->db->getSnapshot();
        $data = $snapshot->exists() && $snapshot->getValue() !== null
            ? $snapshot->getValue()
            : [];

        return array_map([$this, 'toCandidate'], array_keys($data));
    }

    /**
     * Get running candidates (filtered by active election)
     */
    public function getRunnCandidates(): array
    {
        $activeElectionIds = $this->getActiveElectionIds();

        if (empty($activeElectionIds)) {
            return [];
        }

        $snapshot = $this->db->getSnapshot();
        $candidates = [];

        foreach ($snapshot->getValue() ?? [] as $key => $data) {
            // Check if data exists before accessing keys
            if (
                !empty($data['election_id']) &&
                !empty($data['status']) &&
                in_array($data['election_id'], $activeElectionIds, true) &&
                $data['status'] === 'approved'
            ) {
                $candidates[] = $this->toCandidate((string) $key, $data);
            }
        }

        return $candidates;
    }

    public function getRunnCandidatesCount(): int
    {
        $candidates = $this->getRunnCandidates();

        return count($candidates);
    }
}
