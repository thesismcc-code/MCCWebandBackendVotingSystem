<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionAuthMiddleware
{
    private const ROLE_ROUTES = [
        'admin'   => [
            'dashboard',
            'quick-access',
            'manage-accounts',
            'finger-print',
            'voting-logs',
            'election-control',
            'system-activity',
            'reports-and-analytics'
        ],
        'teacher' => [
            'comelec-dashboard',
            'comelec-manage-candidates',
            'student-eligibility',
            'sao-dashboard',
            'sao-candidate-list',
            'sao-voter-participation',
            'sao-final-results'
        ],
        'student' => [
            'students-dashboard',
            'students-profile',
            'students-verification',
            'students-tutorials',
            'students-how-to-vote'
        ],
    ];

    private const ROLE_HOME = [
        'admin'   => 'view.dashboard',
        'teacher' => 'view.comelec-dashboard',
        'student' => 'view.student-dashboard',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Not logged in → redirect to login
        if (!Session::has('auth_user')) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this page.');
        }

        $role        = Session::get('auth_user.role');
        $currentPath = $request->path();

        if (!$this->canAccess($role, $currentPath)) {
            $home = self::ROLE_HOME[$role] ?? 'login';
            return redirect()->route($home)
                ->with('error', 'You are not authorized to access that page.');
        }

        return $next($request);
    }

    private function canAccess(string $role, string $path): bool
    {
        $allowedPaths = self::ROLE_ROUTES[$role] ?? [];

        foreach ($allowedPaths as $allowed) {
            if (str_starts_with($path, $allowed)) {
                return true;
            }
        }

        return false;
    }
}
