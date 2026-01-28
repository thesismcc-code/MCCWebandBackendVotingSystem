<?php

namespace App\Domain\User;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserEmailNotFoundException;
use App\Domain\User\Exceptions\UserPersistenceException;

interface UserRepository
{
    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function findById(int $id): User;

    /**
     * @throws UserEmailNotFoundException
     * @throws UserPersistenceException
     */
    public function findByEmail(string $email): User;

    /**
     * @throws UserPersistenceException
     */
    public function saveNewUser(User $data): User;

    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function updateUser(User $data): User;

    /**
     * @throws UserNotFoundException
     * @throws UserPersistenceException
     */
    public function deleteUser(int $id): void;

    /**
     * @return User[]
     * @throws UserPersistenceException
     */
    public function allUsers(): array;
}
