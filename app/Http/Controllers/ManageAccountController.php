<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use App\Application\RegisterUser\RegisterUser;
use Illuminate\Http\JsonResponse;

class ManageAccountController extends Controller
{
    private $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function index(): View
    {
        return view('manage-accounts');
    }

    private function userValidationRules(): array
    {
        return [
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name'   => 'required|string|max:100',
            'email'       => 'required|email|max:191',
            'password'    => 'required|min:6',
            'role'        => 'required|in:student,teacher,admin',
            'student_id'  => 'nullable|string|max:50',
            'teacher_id'  => 'nullable|string|max:50',
        ];
    }

    private function userValidationMessages(): array
    {
        return [
            'first_name.required'  => 'First name is required.',
            'middle_name.required' => 'Middle name is required.',
            'last_name.required'   => 'Last name is required.',
            'email.required'       => 'We need an email address to send credentials.',
            'email.email'          => 'That email address doesn\'t look valid.',
            'password.required'    => 'Password is required.',
            'password.min'         => 'Password must be at least 6 characters.',
            'role.required'        => 'Please provide a role for this account.',
            'role.in'              => 'Role must be either student, teacher, or admin.',
        ];
    }

    public function newUserAPI(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->userValidationRules(),
            $this->userValidationMessages()
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $user = $this->registerUser->newUser($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'User successfully created.',
                'data'    => $user->toArray(),
            ], 201);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'errors'  => ['email' => [$e->getMessage()]],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
    public function newUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->userValidationRules(),
            $this->userValidationMessages()
        );

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            $this->registerUser->newUser($validator->validated());

            return back()->with('success', 'Account has been created successfully.');

        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
}
