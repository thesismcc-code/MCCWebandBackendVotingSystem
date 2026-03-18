<?php

namespace App\Eloquent\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;

class EloquentUserRepository implements UserRepository
{
    private Reference $db;
    private const COLLECTIONS = 'users';

    public function __construct(Database $db)
    {
        $this->db = $db->getReference(self::COLLECTIONS);
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
            $id = $newRef->getKey();
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
