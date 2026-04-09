<?php

namespace App\Eloquent\Vote;

use App\Domain\Votes\Votes;
use App\Domain\Votes\VotesRepository;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentVoteRepository implements VotesRepository
{
    private Reference $db;
    private Reference $votesDb;
    private Reference $electionsDb;
    private Reference $candidatesDb;
    private Reference $usersDb;

    private const POSITION_ORDER = [
        'President',
        'Vice President',
        'Secretary',
        'Treasurer',
        'Auditor',
        'PRO',
    ];

    public function __construct(Database $db)
    {
        $this->db           = $db->getReference('users');
        $this->votesDb      = $db->getReference('votes');
        $this->electionsDb  = $db->getReference('elections');
        $this->candidatesDb = $db->getReference('candidates');
        $this->usersDb      = $db->getReference('users');
    }

    public function getVotes(int $id): ?Votes
    {
        return null;
    }

    public function getVotesCount(int $id): int
    {
        throw new \Exception('Not implemented');
    }

    public function addVote(Votes $vote): ?Votes
    {
        throw new \Exception('Not implemented');
    }

    public function liveVoteCast(): int
    {
        $electionSnapshot = $this->electionsDb
            ->orderByChild('status')
            ->equalTo('active')
            ->getSnapshot();

        if (!$electionSnapshot->exists() || $electionSnapshot->getValue() === null) {
            return 0;
        }

        $activeElectionId = array_key_first($electionSnapshot->getValue());

        $votesSnapshot = $this->votesDb
            ->orderByChild('election_id')
            ->equalTo($activeElectionId)
            ->getSnapshot();

        if (!$votesSnapshot->exists() || $votesSnapshot->getValue() === null) {
            return 0;
        }

        $uniqueVoterIds = [];
        foreach ($votesSnapshot->getValue() as $vote) {
            if (isset($vote['voter_id'])) {
                $uniqueVoterIds[$vote['voter_id']] = true;
            }
        }

        return count($uniqueVoterIds);
    }

    public function liveCandidateResult(): array
    {
        $electionSnapshot = $this->electionsDb
            ->orderByChild('status')
            ->equalTo('active')
            ->getSnapshot();

        if (!$electionSnapshot->exists() || $electionSnapshot->getValue() === null) {
            return [];
        }

        $activeElections  = $electionSnapshot->getValue();
        $activeElectionId = array_key_first($activeElections);
        $activeElection   = $activeElections[$activeElectionId];

        if (($activeElection['status'] ?? '') !== 'active') {
            return [];
        }

        // ── Step 1: Build approved candidate map for this election ────────────
        $candidatesSnapshot = $this->candidatesDb->getSnapshot();

        $candidateMap = [];
        if ($candidatesSnapshot->exists() && $candidatesSnapshot->getValue() !== null) {
            foreach ($candidatesSnapshot->getValue() as $candId => $candData) {
                if (
                    isset($candData['election_id'], $candData['status']) &&
                    $candData['election_id'] === $activeElectionId &&
                    $candData['status']      === 'approved'
                ) {
                    $candidateMap[$candId] = $candData;
                }
            }
        }

        if (empty($candidateMap)) {
            return [];
        }

        // ── Step 2: Fetch votes for this election ─────────────────────────────
        $votesSnapshot = $this->votesDb
            ->orderByChild('election_id')
            ->equalTo($activeElectionId)
            ->getSnapshot();

        if (!$votesSnapshot->exists() || $votesSnapshot->getValue() === null) {
            return $this->buildZeroResults($candidateMap);
        }

        // ── Step 3: Tally votes per position per candidate ────────────────────
        $tally = [];
        foreach ($votesSnapshot->getValue() as $vote) {
            $position    = $vote['position']     ?? null;
            $candidateId = $vote['candidate_id'] ?? null;

            if (!$position || !$candidateId) continue;

            // Skip votes for candidates not in the approved candidate map
            if (!isset($candidateMap[$candidateId])) continue;

            $tally[$position][$candidateId] = ($tally[$position][$candidateId] ?? 0) + 1;
        }

        // ── Step 4: Resolve full name from /users (cached) ───────────────────
        $nameCache   = [];
        $resolveName = function (string $userId) use (&$nameCache): string {
            if (isset($nameCache[$userId])) {
                return $nameCache[$userId];
            }
            $userData = $this->usersDb->getChild($userId)->getValue();
            if (!$userData) {
                return $nameCache[$userId] = 'Unknown';
            }
            return $nameCache[$userId] = trim(
                ($userData['first_name']  ?? '') . ' ' .
                    ($userData['middle_name'] ?? '') . ' ' .
                    ($userData['last_name']   ?? '')
            ) ?: 'Unknown';
        };

        // ── Step 5: Build results array ───────────────────────────────────────
        $results = [];

        foreach ($tally as $position => $candidateVotes) {
            $totalVotesInPosition = array_sum($candidateVotes);
            $positionResults      = [];

            foreach ($candidateVotes as $candidateId => $voteCount) {
                $candData = $candidateMap[$candidateId];
                $userId   = $candData['user_id'] ?? $candidateId;

                $positionResults[] = [
                    'candidate_id'  => $candidateId,
                    'name'          => $resolveName($userId),
                    'party_list_id' => $candData['party_list_id'] ?? null,
                    'votes'         => $voteCount,
                    'percentage'    => $totalVotesInPosition > 0
                        ? round(($voteCount / $totalVotesInPosition) * 100, 2)
                        : 0.0,
                ];
            }

            // Sort candidates within each position by votes descending
            usort($positionResults, fn($a, $b) => $b['votes'] <=> $a['votes']);

            $results[$position] = $positionResults;
        }

        // ── Step 6: Sort positions by defined order ───────────────────────────
        uksort($results, function (string $a, string $b): int {
            $orderA = array_search($a, self::POSITION_ORDER);
            $orderB = array_search($b, self::POSITION_ORDER);

            $orderA = $orderA === false ? PHP_INT_MAX : $orderA;
            $orderB = $orderB === false ? PHP_INT_MAX : $orderB;

            return $orderA <=> $orderB;
        });

        return $results;
    }

    private function buildZeroResults(array $candidateMap): array
    {
        $nameCache   = [];
        $resolveName = function (string $userId) use (&$nameCache): string {
            if (isset($nameCache[$userId])) {
                return $nameCache[$userId];
            }
            $userData = $this->usersDb->getChild($userId)->getValue();
            if (!$userData) {
                return $nameCache[$userId] = 'Unknown';
            }
            return $nameCache[$userId] = trim(
                ($userData['first_name']  ?? '') . ' ' .
                    ($userData['middle_name'] ?? '') . ' ' .
                    ($userData['last_name']   ?? '')
            ) ?: 'Unknown';
        };

        $results = [];
        foreach ($candidateMap as $candidateId => $candData) {
            $position = $candData['position'] ?? 'Unknown';
            $userId   = $candData['user_id']  ?? $candidateId;

            $results[$position][] = [
                'candidate_id'  => $candidateId,
                'name'          => $resolveName($userId),
                'party_list_id' => $candData['party_list_id'] ?? null,
                'votes'         => 0,
                'percentage'    => 0.0,
            ];
        }

        // Sort positions by defined order
        uksort($results, function (string $a, string $b): int {
            $orderA = array_search($a, self::POSITION_ORDER);
            $orderB = array_search($b, self::POSITION_ORDER);

            $orderA = $orderA === false ? PHP_INT_MAX : $orderA;
            $orderB = $orderB === false ? PHP_INT_MAX : $orderB;

            return $orderA <=> $orderB;
        });

        return $results;
    }
    private function getActiveElectionID(): ?string
    {
        $snapshot = $this->electionsDb->orderByChild('status')->equalTo('active')->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return null;
        }

        return array_key_first($snapshot->getValue());
    }
    public function getVotingLogs(int $perPage, ?string $search, ?string $course, ?string $yearLevel): LengthAwarePaginator
    {
        $electionID = $this->getActiveElectionId();
        if (!$electionID) {
            return $this->emptyPagenator($perPage);
        }
        $voteSnapshot = $this->votesDb
            ->orderByChild('election_id')
            ->equalTo($electionID)
            ->getSnapshot();

        if (!$voteSnapshot->exists() || $voteSnapshot->getValue() === null) {
            return $this->emptyPagenator($perPage);
        }

        $voterTimestamps = [];
        foreach ($voteSnapshot->getValue() as $vote) {
            $voterId = $vote['voter_id'] ?? null;
            if (!$voterId) continue;

            if (!isset($voterTimestamps[$voterId])) {
                $voterTimestamps[$voterId] = $vote['created_at'] ?? null;
            }
        }

        if (empty($voterTimestamps)) {
            return $this->emptyPagenator($perPage);
        }

        $allUsers = $this->usersDb->getValue() ?? [];

        $logs = [];

        foreach ($voterTimestamps as $voterID => $votedAt) {
            $user = $allUsers[$voterID] ?? null;

            if (!$user || ($user['role'] ?? '') !== 'student') continue;
            if ($course && strtolower($user['course'] ?? '') !== strtolower($course)) continue;
            if ($yearLevel && strtolower($user['year_level'] ?? '') !== strtolower($yearLevel)) continue;

            $fullname = trim(($user['first_name'] ?? '') . ' ' . ($user['middle_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));

            if ($search) {
                $needle = strtolower($search);
                $matchID = str_contains(strtolower($user['student_id'] ?? ''), $needle);
                $matchName = str_contains(strtolower($fullname), $needle);

                if (!$matchID && !$matchName) continue;
            }

            $logs[] = [
                'voter_id'   => $voterID,
                'student_id' => $user['student_id'] ?? 'Unknown',
                'name'       => $fullname,
                'course'     => $user['course'],
                'year_level' => $user['year_level'],
                'voted_at'   => $votedAt,
                'status'     => 'Voted',
            ];
        }

        usort($logs, fn($a, $b) => strcmp($b['voted_at'], $a['voted_at']));

        $total = count($logs);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = array_slice($logs, ($currentPage - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
    private function emptyPagenator(int $perPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            [],
            0,
            $perPage,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
    public function getAllVotingLogsForExport(?string $search = null, ?string $course = null, ?string $yearLevel = null): array
    {
        $elecitonID = $this->getActiveElectionID();
        $electionName = $this->getActiveElectionName();

        if (!$elecitonID) {
            return ['election_name' => $electionName, 'logs' => []];
        }

        $votesSnapshot = $this->votesDb
            ->orderByChild('election_id')
            ->equalTo($elecitonID)
            ->getSnapshot();

        if (!$votesSnapshot->exists() || $votesSnapshot->getValue() === null) {
            return ['election_name' => $electionName, 'logs' => []];
        }

        $voterTimestamps = [];
        foreach ($votesSnapshot->getValue() as $vote) {
            $voterId = $vote['voter_id'] ?? null;
            if (!$voterId) continue;
            if (!isset($voterTimestamps[$voterId])) {
                $voterTimestamps[$voterId] = $vote['created_at'] ?? '';
            }
        }

        $allUsers = $this->usersDb->getValue() ?? [];
        $logs     = [];

        foreach ($voterTimestamps as $voterId => $votedAt) {
            $user = $allUsers[$voterId] ?? null;
            if (!$user || ($user['role'] ?? '') !== 'student') continue;

            if ($course    && strtolower($user['course']     ?? '') !== strtolower($course))    continue;
            if ($yearLevel && strtolower($user['year_level'] ?? '') !== strtolower($yearLevel)) continue;

            $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));

            if ($search) {
                $needle = strtolower($search);
                if (
                    !str_contains(strtolower($user['student_id'] ?? ''), $needle) &&
                    !str_contains(strtolower($fullName), $needle)
                ) continue;
            }

            $logs[] = [
                'student_id' => $user['student_id'] ?? '',
                'name'       => $fullName,
                'course'     => $user['course']     ?? '',
                'year_level' => $user['year_level'] ?? '',
                'voted_at'   => $votedAt,
                'status'     => 'Voted',
            ];
        }

        usort($logs, fn($a, $b) => strcmp($b['voted_at'], $a['voted_at']));

        return [
            'election_name' => $electionName,
            'logs'          => $logs,
        ];
    }
    private function getActiveElectionName(): string
    {
        $snapshot = $this->electionsDb->orderByChild('status')->equalTo('active')->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return 'N/A';
        }
        $election = array_values($snapshot->getValue())[0] ?? null;

        return $election['name'] ?? 'N/A';
    }
}
