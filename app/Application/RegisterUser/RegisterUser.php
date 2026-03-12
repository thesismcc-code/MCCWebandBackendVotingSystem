<?php

namespace App\Application\RegisterUser;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Illuminate\Support\Str;

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

        $adminid = null;
        $studentId = null;
        $teacherId = null;

        if ($data['role'] === 'admin') {
            $adminid = $this->generateID('admin');
        }

        if ($data['role'] === 'student') {
            $studentId = $this->generateID('student');
        }

        if ($data['role'] === 'teacher') {
            $teacherId = $this->generateID('teacher');
        }

        $user = new User(
            id: null,
            first_name: $data['first_name'],
            middle_name: $data['middle_name'],
            last_name: $data['last_name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
            admin_id: $adminid,
            student_id: $studentId,
            teacher_id: $teacherId,
            email_verified_at: null,
            created_at: null,
            updated_at: null,
        );

        return $this->userRepository->saveNewUser($user);
    }

    private function generateID(string $role): string
    {
        $prefix = match ($role) {
            'admin' => 'ADM',
            'student' => 'STU',
            'teacher' => 'THR',
            default => throw new \InvalidArgumentException('Invalid role')
        };

        return $prefix . Str::random(12);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->allUsers();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
}
