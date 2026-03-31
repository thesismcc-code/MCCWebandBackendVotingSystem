<?php

namespace App\Application\RegisterUser;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class RegisterUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function newUser($data): ?User
    {
        $existing = $this->userRepository->findByEmail($data["email"]);

        if ($existing) {
            throw new \InvalidArgumentException('That email address is already taken.');
        }

        $adminId   = null;
        $studentId = null;
        $teacherId = null;
        $userId    = null;

        if ($data['role'] === 'admin') {
            $userId  = $this->generateID('admin');
            $adminId = $userId;
        }

        if ($data['role'] === 'student') {
            $userId    = $this->generateID('student');
            $studentId = $this->generateStudentID();
        }

        if ($data['role'] === 'comelec') {
            $userId    = $this->generateID('comelec');
            $teacherId = $userId;
        }

        if ($data['role'] === 'sao') {
            $userId    = $this->generateID('sao');
            $teacherId = $userId;
        }

        $user = new User(
            id: $userId,
            first_name: $data['first_name'],
            middle_name: $data['middle_name'],
            last_name: $data['last_name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
            admin_id: $adminId,
            student_id: $studentId,
            comelec_id: $teacherId,
            email_verified_at: null,
            created_at: null,
            updated_at: null,
        );

        return $this->userRepository->saveNewUser($user);
    }

    private function generateID(string $role): string
    {
        $prefix = match ($role) {
            'admin'   => 'ADM',
            'student' => 'STU',
            'comelec' => 'COM',
            'sao'     => 'SAO',
            default   => throw new \InvalidArgumentException('Invalid role')
        };

        return $prefix . Str::random(12);
    }

    private function generateStudentID(): string
    {
        $yearSuffix = substr(date('Y'), 1); // e.g. "026" for 2026

        $prefix   = 'STU-' . $yearSuffix . '-';
        $count    = $this->userRepository->countStudentsByYearPrefix($prefix);
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . $sequence;
    }

    public function getAllUsers(int $perPage, ?string $schoolYearFilter = null): LengthAwarePaginator
    {
        return $this->userRepository->allUsers($perPage, $schoolYearFilter);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function total_registered_voters(): int
    {
        return $this->userRepository->countStudentVoters();
    }
    public function turnOutRates(): array
    {
        return $this->userRepository->getVoterTurnout();
    }

    public function realtimeVoterTurnout(): array
    {
        return $this->userRepository->realtimeVoterTurnout();
    }
    public function voterTurnoutByYearLevel(): array
    {
        return $this->userRepository->voterTurnoutByYearLevel();
    }
    public function countUsersSummary(): array
    {
        return $this->userRepository->countUsersSummary();
    }
}
