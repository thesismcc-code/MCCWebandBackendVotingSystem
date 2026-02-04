<?php

namespace App\Http\Controllers;

use App\Application\RegisterUser\RegisterUser;
use App\Domain\User\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private RegisterUser $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function getAllUsers(): JsonResponse
    {
        $users = $this->registerUser->getAllUsers();

        $users = array_map(
            fn (User $user) => $user->toArray(),
            $users
        );

        return response()->json(['users' => $users]);
    }
}
