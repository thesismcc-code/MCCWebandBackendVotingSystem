<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;
use Illuminate\Http\Request;

class UserTestController extends Controller
{
    protected $database;
    protected $auth;

    public function __construct(Database $database, Auth $auth)
    {
        $this->database = $database;
        $this->auth = $auth;
    }

    public function index(): JsonResponse
    {
        // Get all users
        $users = $this->database->getReference("users")->getValue();

        return response()->json([
            "status" => "success",
            "data" => $users,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // Validate the request
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "role" => "required|string|max:50",
        ]);

        // Create a new user with auto-generated ID (push)
        $newUserRef = $this->database->getReference("users")->push([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "role" => $validated["role"],
            "created_at" => now()->toDateTimeString(),
        ]);

        return response()->json(
            [
                "status" => "success",
                "message" => "User created successfully",
                "id" => $newUserRef->getKey(),
                "data" => $newUserRef->getValue(),
            ],
            201,
        );
    }

    public function show(string $id): JsonResponse
    {
        // Get a specific user
        $user = $this->database->getReference("users/" . $id)->getValue();

        if (!$user) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "User not found",
                ],
                404,
            );
        }

        return response()->json([
            "status" => "success",
            "data" => $user,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // Validate the request
        $validated = $request->validate([
            "name" => "sometimes|string|max:255",
            "email" => "sometimes|email|max:255",
            "role" => "sometimes|string|max:50",
        ]);

        // Check if user exists
        $user = $this->database->getReference("users/" . $id)->getValue();

        if (!$user) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "User not found",
                ],
                404,
            );
        }

        // Update the user
        $this->database->getReference("users/" . $id)->update(
            array_merge($validated, [
                "updated_at" => now()->toDateTimeString(),
            ]),
        );

        // Get updated user
        $updatedUser = $this->database
            ->getReference("users/" . $id)
            ->getValue();

        return response()->json([
            "status" => "success",
            "message" => "User updated successfully",
            "data" => $updatedUser,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        // Check if user exists
        $user = $this->database->getReference("users/" . $id)->getValue();

        if (!$user) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "User not found",
                ],
                404,
            );
        }

        // Delete the user
        $this->database->getReference("users/" . $id)->remove();

        return response()->json(
            [
                "status" => "success",
                "message" => "User deleted successfully",
            ],
            200,
        );
    }
}
