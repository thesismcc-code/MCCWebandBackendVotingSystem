<?php
namespace App\Application\RegisterUser;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

class RegisterUser{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->allUsers();
    }
}
