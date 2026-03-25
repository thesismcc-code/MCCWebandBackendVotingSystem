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

        // Collect unique voter IDs — scoped to active elections only if provided
        $uniqueVoterIds = [];
        foreach ($votesSnapshot->getValue() as $vote) {
            if (!isset($vote['voter_id'])) {
                continue;
            }

            // Skip votes that don't belong to an active election
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
