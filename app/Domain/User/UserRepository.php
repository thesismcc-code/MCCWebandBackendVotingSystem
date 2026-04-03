<?php

namespace App\Domain\User;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserEmailNotFoundException;
use App\Domain\User\Exceptions\UserPersistenceException;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepository
{
    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function findById(string $id): ?User;

    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function findByStudentID(string $studentId): ?User;

    /**
     * @throws UserEmailNotFoundException
     * @throws UserPersistenceException
     */
    public function findByEmail(string $email): ?User;

    /**
     * @throws UserPersistenceException
     */
    public function saveNewUser(User $data): ?User;

    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function updateUser(User $data): ?User;

    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function deleteUser(string $id): void;

    /**
     * @return LengthAwarePaginator
     * @throws UserPersistenceException
     */
    public function allUsers(int $perPage, ?string $schoolYearFilter = null): LengthAwarePaginator;

    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function countStudentsByYearPrefix(string $prefix): int;

    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function validateStudentID(string $studentId): bool;

    public function countStudentVoters(): int;
    public function countTotalStudents(): int;
    public function countTotalStudentsEnrolledToday(): int;
    public function getVoterTurnout(): array;
    public function realtimeVoterTurnout(): array;
    public function voterTurnoutByYearLevel(): array;
    public function countUsersSummary(): array;
    public function getUserExceptStudents(int $perPage, ?string $schoolYearFilter = null): LengthAwarePaginator;
    public function getUserAllStudents(int $perPage, ?string $student_id = null, ?string $course = null, ?string $year_level = null): LengthAwarePaginator;
}
