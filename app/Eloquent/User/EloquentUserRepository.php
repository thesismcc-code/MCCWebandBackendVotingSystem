<?php

namespace App\Eloquent\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;

class EloquentUserRepository implements UserRepository
{
    private Reference $db;
    private Reference $votesDb;
    private Reference $electionsDb;
    private const COLLECTIONS            = 'users';
    private const VOTES_COLLECTIONS      = 'votes';
    private const ELECTIONS_COLLECTIONS  = 'elections';

    public function __construct(Database $db)
    {
        $this->db          = $db->getReference(self::COLLECTIONS);
        $this->votesDb     = $db->getReference(self::VOTES_COLLECTIONS);
        $this->electionsDb = $db->getReference(self::ELECTIONS_COLLECTIONS);
    }

    public function findById(string $id): ?User
    {
        $data = $this->db->getChild($id)->getValue();
        if (!$data) return null;

        return $this->toUserWithPassword($id, $data);
    }

    public function findByEmail(string $email): ?User
    {
        $collection = $this->db->getSnapshot();
        if (!$collection->exists() || $collection->getValue() === null) {
            return null;
        }

        $snapshot = $this->db
            ->orderByChild('email')
            ->equalTo($email)
            ->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return null;
        }

        foreach ($snapshot->getValue() as $key => $data) {
            return $this->toUserWithPassword((string) $key, $data);
        }

        return null;
    }

    public function findByStudentID(string $studentId): ?User
    {
        $snapshot = $this->db
            ->orderByChild('student_id')
            ->equalTo($studentId)
            ->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return null;
        }

        foreach ($snapshot->getValue() as $key => $data) {
            if (isset($data['role']) && $data['role'] === 'student') {
                return $this->toUserWithPassword((string) $key, $data);
            }
        }

        return null;
    }

    public function validateStudentID(string $studentId): bool
    {
        $snapshot = $this->db
            ->orderByChild('student_id')
            ->equalTo($studentId)
            ->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return false;
        }

        foreach ($snapshot->getValue() as $data) {
            if (isset($data['role']) && $data['role'] === 'student') {
                return true;
            }
        }

        return false;
    }

    public function saveNewUser(User $data): ?User
    {
        $now = Carbon::now()->toDateTimeLocalString();

        $payload = [
            ...$data->toArray(),
            'password'   => Hash::make($data->getPassword()),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        if ($data->getId()) {
            $this->db->getChild($data->getId())->set($payload);
            $id = $data->getId();
        } else {
            $newRef = $this->db->push($payload);
            $id     = $newRef->getKey();
        }

        return $this->toUser((string) $id, [...$payload, 'id' => $id]);
    }

    public function updateUser(User $data): ?User
    {
        $now = Carbon::now()->toDateTimeLocalString();

        $payload = [
            ...$data->toArray(),
            'updated_at' => $now,
        ];

        $this->db->getChild($data->getId())->update($payload);

        return $this->toUser($data->getId(), $payload);
    }

    public function deleteUser(string $id): void
    {
        $this->db->getChild($id)->remove();
    }

    public function allUsers(): array
    {
        $snapshot = $this->db->getSnapshot();
        if (!$snapshot->exists() || $snapshot->getValue() === null) return [];

        $users = [];
        foreach ($snapshot->getValue() as $key => $data) {
            $users[] = $this->toUser((string) $key, $data);
        }

        return $users;
    }

    public function countStudentsByYearPrefix(string $prefix): int
    {
        $snapshot = $this->db
            ->orderByChild('student_id')
            ->startAt($prefix)
            ->endAt($prefix . '\uf8ff')
            ->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return 0;
        }

        $count = 0;

        foreach ($snapshot->getValue() as $data) {
            if (
                isset($data['role'], $data['student_id']) &&
                $data['role'] === 'student' &&
                str_starts_with($data['student_id'], $prefix)
            ) {
                $count++;
            }
        }

        return $count;
    }

    private function getActiveElectionIds(): array
    {
        $snapshot = $this->electionsDb
            ->orderByChild('status')
            ->equalTo('active')
            ->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return [];
        }

        return array_keys($snapshot->getValue());
    }

    public function countStudentVoters(?array $activeElectionIds = null): int
    {
        $votesSnapshot = $this->votesDb->getSnapshot();

        if (!$votesSnapshot->exists() || $votesSnapshot->getValue() === null) {
            return 0;
        }

        $uniqueVoterIds = [];
        foreach ($votesSnapshot->getValue() as $vote) {
            if (!isset($vote['voter_id'])) {
                continue;
            }

            if (
                $activeElectionIds !== null &&
                (!isset($vote['election_id']) || !in_array($vote['election_id'], $activeElectionIds, true))
            ) {
                continue;
            }

            $uniqueVoterIds[$vote['voter_id']] = true;
        }

        if (empty($uniqueVoterIds)) {
            return 0;
        }

        $allUsers = $this->db->getValue();

        if (empty($allUsers)) {
            return 0;
        }

        return count(array_filter(
            array_intersect_key($allUsers, $uniqueVoterIds),
            fn($user) => isset($user['role']) && $user['role'] === 'student'
        ));
    }

    public function countTotalStudents(): int
    {
        $snapshot = $this->db
            ->orderByChild('role')
            ->equalTo('student')
            ->getSnapshot();

        if (!$snapshot->exists() || $snapshot->getValue() === null) {
            return 0;
        }

        return count($snapshot->getValue());
    }

    public function getVoterTurnout(): array
    {
        $activeElectionIds = $this->getActiveElectionIds();

        if (empty($activeElectionIds)) {
            return [
                'voters'          => 0,
                'total_students'  => $this->countTotalStudents(),
                'turnout_percent' => 0.0,
            ];
        }

        $voters        = $this->countStudentVoters($activeElectionIds);
        $totalStudents = $this->countTotalStudents();

        return [
            'voters'          => $voters,
            'total_students'  => $totalStudents,
            'turnout_percent' => $totalStudents > 0
                ? round(($voters / $totalStudents) * 100, 2)
                : 0.0,
        ];
    }

    /**
     * Returns real-time voter turnout stats scoped to active elections.
     *
     * @return array{
     *   total_students: int,
     *   voted_count: int,
     *   not_yet_voted: int,
     *   turnout_percent: float
     * }
     */
    public function realtimeVoterTurnout(): array
    {
        $activeElectionIds = $this->getActiveElectionIds();
        $totalStudents     = $this->countTotalStudents();

        if (empty($activeElectionIds) || $totalStudents === 0) {
            return [
                'total_students'  => $totalStudents,
                'voted_count'     => 0,
                'not_yet_voted'   => $totalStudents,
                'turnout_percent' => 0.0,
            ];
        }

        $votedCount = $this->countStudentVoters($activeElectionIds);

        return [
            'total_students'  => $totalStudents,
            'voted_count'     => $votedCount,
            'not_yet_voted'   => $totalStudents - $votedCount,
            'turnout_percent' => round(($votedCount / $totalStudents) * 100, 2),
        ];
    }

    /**
     * Returns voter turnout broken down by year level, derived from enrollment year
     * encoded in the student ID (format: STU-{enrollYear}-{seq}, e.g. STU-2023-001).
     *
     * Year level is calculated as: currentYear - enrollYear + 1
     *   STU-2023-xxx in 2026 → 4th Year
     *   STU-2024-xxx in 2026 → 3rd Year
     *   STU-2025-xxx in 2026 → 2nd Year
     *   STU-2026-xxx in 2026 → 1st Year
     *
     * Results are scoped to active elections and sorted from 1st Year to 4th Year.
     *
     * @return array<int, array{
     *   year_level: string,
     *   enroll_year: int,
     *   total_students: int,
     *   voted: int,
     *   not_yet_voted: int,
     *   turnout_percent: float
     * }>
     */
    public function voterTurnoutByYearLevel(): array
    {
        $activeElectionIds = $this->getActiveElectionIds();
        $currentYear       = (int) date('Y');

        // Collect unique voter IDs scoped to active elections
        $votedByStudent = [];

        if (!empty($activeElectionIds)) {
            $votesSnapshot = $this->votesDb->getSnapshot();

            if ($votesSnapshot->exists() && $votesSnapshot->getValue() !== null) {
                foreach ($votesSnapshot->getValue() as $vote) {
                    if (
                        isset($vote['voter_id'], $vote['election_id']) &&
                        in_array($vote['election_id'], $activeElectionIds, true)
                    ) {
                        $votedByStudent[$vote['voter_id']] = true;
                    }
                }
            }
        }

        $allUsers = $this->db->getValue();

        if (empty($allUsers)) {
            return [];
        }

        // Group students by enrollment year extracted from student ID.
        // Student ID format: STU-{enrollYear}-{seq}  e.g. STU-2023-001
        // Parts after explode('-'): ['STU', '2023', '001']
        $yearGroups = [];

        foreach ($allUsers as $userId => $user) {
            if (!isset($user['role'], $user['student_id']) || $user['role'] !== 'student') {
                continue;
            }

            $parts      = explode('-', $user['student_id']);
            $enrollYear = isset($parts[1]) && is_numeric($parts[1]) ? (int) $parts[1] : null;

            if ($enrollYear === null) {
                continue;
            }

            if (!isset($yearGroups[$enrollYear])) {
                $yearGroups[$enrollYear] = ['total' => 0, 'voted' => 0];
            }

            $yearGroups[$enrollYear]['total']++;

            if (isset($votedByStudent[$userId])) {
                $yearGroups[$enrollYear]['voted']++;
            }
        }

        // Sort ascending by enrollment year so 1st Year appears first
        ksort($yearGroups);

        $result = [];

        foreach ($yearGroups as $enrollYear => $counts) {
            $total     = $counts['total'];
            $voted     = $counts['voted'];
            $yearLevel = $currentYear - $enrollYear + 1;

            $label = match (true) {
                $yearLevel === 1 => '1st Year',
                $yearLevel === 2 => '2nd Year',
                $yearLevel === 3 => '3rd Year',
                $yearLevel === 4 => '4th Year',
                $yearLevel > 4   => "{$yearLevel}th Year",
                default          => "Year {$yearLevel}",
            };

            $result[] = [
                'year_level'      => $label,
                'enroll_year'     => $enrollYear,
                'total_students'  => $total,
                'voted'           => $voted,
                'not_yet_voted'   => $total - $voted,
                'turnout_percent' => $total > 0 ? round(($voted / $total) * 100, 2) : 0.0,
            ];
        }

        return $result;
    }

    private function toUser(mixed $id, array $data): User
    {
        return new User(
            id: (string) $id,
            first_name: $data['first_name'] ?? '',
            middle_name: $data['middle_name'] ?? '',
            last_name: $data['last_name'] ?? '',
            email: $data['email'] ?? '',
            password: '',
            role: $data['role'] ?? '',
            admin_id: $data['admin_id'] ?? null,
            student_id: $data['student_id'] ?? null,
            teacher_id: $data['teacher_id'] ?? null,
            email_verified_at: $data['email_verified_at'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
        );
    }

    private function toUserWithPassword(mixed $id, array $data): User
    {
        return new User(
            id: (string) $id,
            first_name: $data['first_name'] ?? '',
            middle_name: $data['middle_name'] ?? '',
            last_name: $data['last_name'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            role: $data['role'] ?? '',
            admin_id: $data['admin_id'] ?? null,
            student_id: $data['student_id'] ?? null,
            teacher_id: $data['teacher_id'] ?? null,
            email_verified_at: $data['email_verified_at'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
        );
    }
}
