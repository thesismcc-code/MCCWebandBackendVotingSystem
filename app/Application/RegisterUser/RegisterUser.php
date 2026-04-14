<?php

namespace App\Application\RegisterUser;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $existing = $this->userRepository->findByEmail($data['email']);

        if ($existing) {
            throw new \InvalidArgumentException('That email address is already taken.');
        }

        $adminId = null;
        $studentId = null;
        $teacherId = null;
        $userId = null;

        if ($data['role'] === 'admin') {
            $userId = $this->generateID('admin');
            $adminId = $userId;
        }

        if ($data['role'] === 'student') {
            $userId = $this->generateID('student');
            $studentId = $this->generateStudentID();
        }

        if ($data['role'] === 'comelec') {
            $userId = $this->generateID('comelec');
            $teacherId = $userId;
        }

        if ($data['role'] === 'sao') {
            $userId = $this->generateID('sao');
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

    public function updateUser(array $data): ?User
    {
        $existing = $this->userRepository->findById($data['user_id']);

        if (! $existing) {
            throw new \InvalidArgumentException('User not found.');
        }

        if (($data['email'] ?? '') !== $existing->getEmail()) {
            $withEmail = $this->userRepository->findByEmail($data['email']);

            if ($withEmail && $withEmail->getId() !== $existing->getId()) {
                throw new \InvalidArgumentException('That email address is already taken.');
            }
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $role = $data['role'];
        $adminId = $existing->getAdminId();
        $studentId = $existing->getStudentId();
        $comelecId = $existing->getComelecId();
        $course = $existing->getCourse();
        $yearLevel = $existing->getYearLevel();

        if ($role === 'student') {
            $studentId = $data['student_id'] ?? $studentId;
            $course = $data['course'] ?? $course;
            $yearLevel = $data['year_level'] ?? $yearLevel;
        }

        $user = new User(
            id: $data['user_id'],
            first_name: $data['first_name'],
            middle_name: $data['middle_name'],
            last_name: $data['last_name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            role: $role,
            admin_id: $adminId,
            student_id: $studentId,
            course: $course,
            year_level: $yearLevel,
            comelec_id: $comelecId,
            email_verified_at: $existing->getEmailVerifiedAt(),
            created_at: $existing->getCreatedAt(),
            updated_at: null,
        );

        return $this->userRepository->updateUser($user);
    }

    private function generateID(string $role): string
    {
        $prefix = match ($role) {
            'admin' => 'ADM',
            'student' => 'STU',
            'comelec' => 'COM',
            'sao' => 'SAO',
            default => throw new \InvalidArgumentException('Invalid role')
        };

        return $prefix.Str::random(12);
    }

    private function generateStudentID(): string
    {
        $yearSuffix = substr(date('Y'), 1); // e.g. "026" for 2026

        $prefix = 'STU-'.$yearSuffix.'-';
        $count = $this->userRepository->countStudentsByYearPrefix($prefix);
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return $prefix.$sequence;
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

    public function deleteUser(string $id): void
    {
        $this->userRepository->deleteUser($id);
    }

    public function getUserExceptStudents(int $perPage, ?string $schoolYearFilter = null): LengthAwarePaginator
    {
        return $this->userRepository->getUserExceptStudents($perPage, $schoolYearFilter);
    }

    public function countTotalStudents(): int
    {
        return $this->userRepository->countTotalStudents();
    }

    public function countTotalStudentsEnrolledToday(): int
    {
        return $this->userRepository->countTotalStudentsEnrolledToday();
    }

    public function getUserAllStudents(int $perPage, ?string $student_id = null, ?string $course = null, ?string $year_level = null): LengthAwarePaginator
    {
        return $this->userRepository->getUserAllStudents($perPage, $student_id, $course, $year_level);
    }

    public function getUniqueCourses(): array
    {
        return $this->userRepository->getUniqueCourses();
    }
}
