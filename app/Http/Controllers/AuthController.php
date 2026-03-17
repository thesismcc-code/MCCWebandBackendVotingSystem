<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Application\RegisterAuth\RegisterAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    private RegisterAuth $registerAuth;

    public function __construct(RegisterAuth $registerAuth)
    {
        $this->registerAuth = $registerAuth;
    }

    public function index()
    {
        if (Session::has('auth_user')) {
            return $this->redirectByRole(Session::get('auth_user.role'));
        }

        return view('index');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ], $this->authValidationMessages());

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            $user = $this->registerAuth->login(
                $request->input('email'),
                $request->input('password')
            );

            return $this->redirectByRole($user->getRole());

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

    public function logout()
    {
        $userId = Session::get('auth_user.id', '');
        $this->registerAuth->logout($userId);

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    // ── API / JWT ─────────────────────────────────────────────────

    public function loginAPI(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ], $this->authValidationMessages());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $token = $this->registerAuth->loginJwt(
                $request->input('email'),
                $request->input('password')
            );

            return response()->json([
                'success'      => true,
                'message'      => 'Login successful.',
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => config('jwt.ttl') * 60,
            ], 200);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function logoutAPI(Request $request): JsonResponse
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not provided.',
                ], 401);
            }

            $this->registerAuth->logoutJwt((string) $token);

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
            ], 200);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token has already expired.',
            ], 401);

        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid.',
            ], 401);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout. Please try again.',
            ], 500);
        }
    }

    public function meAPI(Request $request): JsonResponse
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'          => $payload->get('sub'),
                    'email'       => $payload->get('email'),
                    'role'        => $payload->get('role'),
                    'first_name'  => $payload->get('first_name'),
                    'last_name'   => $payload->get('last_name'),
                    'student_id'  => $payload->get('student_id'),
                    'teacher_id'  => $payload->get('teacher_id'),
                    'admin_id'    => $payload->get('admin_id'),
                ],
            ], 200);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired.',
            ], 401);

        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid.',
            ], 401);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided.',
            ], 401);
        }
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'   => redirect()->route('view.dashboard'),           // ← /dashboard
            'teacher' => redirect()->route('view.comelec-dashboard'),   // ← /comelec-dashboard
            'student' => redirect()->route('view.student-dashboard'),   // ← /students-dashboard
            default   => redirect()->route('login'),
        };
    }

    private function authValidationMessages(): array
    {
        return [
            'email.required'    => 'Email is required.',
            'email.email'       => 'That email address doesn\'t look valid.',
            'password.required' => 'Password is required.',
        ];
    }
}
