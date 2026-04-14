<?php

namespace App\Application\SystemActivity;

use App\Domain\SystemActivity\SystemActivity;
use App\Domain\SystemActivity\SystemActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RegisterSystemActivity
{
    public const PER_PAGE = 5;

    public function __construct(
        private SystemActivityRepository $systemActivityRepository
    ) {}

    public function getAllSystemActivities(): array
    {
        return $this->systemActivityRepository->getAllSystemActivities();
    }

    public function paginateRealtimeLogs(int $page): LengthAwarePaginator
    {
        return $this->systemActivityRepository->paginateByLevelGroup($page, self::PER_PAGE, 'realtime');
    }

    public function paginateErrorLogs(int $page): LengthAwarePaginator
    {
        return $this->systemActivityRepository->paginateByLevelGroup($page, self::PER_PAGE, 'error');
    }

    /**
     * @return array<int, SystemActivity>
     */
    public function getErrorLogsSince(string $sinceIso): array
    {
        return $this->systemActivityRepository->getErrorLogsSince($sinceIso);
    }

    /**
     * Persist a failed login or API auth attempt to Firebase (warning / error group for the Error Logs tab).
     *
     * @param  'warning'|'error'  $level
     * @param  'session'|'guest'  $authChannel Web form flows use session; API login failures use guest.
     */
    public function recordFailedLoginAttempt(
        Request $request,
        string $summary,
        string $level = 'warning',
        string $authChannel = 'session',
        string $httpStatus = '',
    ): void {
        if (! in_array($level, ['warning', 'error'], true)) {
            $level = 'warning';
        }

        try {
            $routeName = $request->route()?->getName() ?? '';
            $now = now()->toIso8601String();
            $entity = new SystemActivity(
                id: '',
                userId: 'guest',
                level: $level,
                activity: $summary,
                createdAt: $now,
                updatedAt: $now,
                role: 'guest',
                httpStatus: $httpStatus,
                routeName: $routeName,
                ipAddress: $request->ip() ?? '',
                authChannel: $authChannel,
            );

            $this->systemActivityRepository->createSystemActivity($entity);
        } catch (\Throwable $e) {
            Log::error('RegisterSystemActivity::recordFailedLoginAttempt — '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    public function recordHttpActivity(Request $request, SymfonyResponse $response): void
    {
        try {
            $statusCode = $response->getStatusCode();
            $level = match (true) {
                $statusCode >= 500 => 'error',
                $statusCode >= 400 => 'warning',
                default => 'info',
            };

            $actor = $this->resolveActor();

            $route = $request->route();
            $routeName = $route?->getName() ?? '';
            $path = '/'.$request->path();
            $path = $path === '//' ? '/' : $path;
            $method = $request->method();
            $activity = sprintf(
                '%s %s HTTP %d',
                $method,
                $path,
                $statusCode
            );
            if ($routeName !== '') {
                $activity .= sprintf(' (%s)', $routeName);
            }

            $now = now()->toIso8601String();
            $entity = new SystemActivity(
                id: '',
                userId: $actor['userId'],
                level: $level,
                activity: $activity,
                createdAt: $now,
                updatedAt: $now,
                role: $actor['role'],
                httpStatus: (string) $statusCode,
                routeName: $routeName,
                ipAddress: $request->ip() ?? '',
                authChannel: $actor['authChannel'],
            );

            $this->systemActivityRepository->createSystemActivity($entity);
        } catch (\Throwable $e) {
            Log::error('RegisterSystemActivity::recordHttpActivity — '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    /**
     * @return array{userId: string, role: string, authChannel: string}
     */
    private function resolveActor(): array
    {
        $authUser = Session::get('auth_user');
        if (is_array($authUser)) {
            return [
                'userId' => (string) ($authUser['id'] ?? 'guest'),
                'role' => (string) ($authUser['role'] ?? 'guest'),
                'authChannel' => 'session',
            ];
        }

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $role = 'user';
            if ($user !== null) {
                $role = (string) (data_get($user, 'role') ?: 'user');
            }

            return [
                'userId' => (string) ($user?->getAuthIdentifier() ?? 'guest'),
                'role' => $role,
                'authChannel' => 'jwt',
            ];
        }

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $role = 'user';
            if ($user !== null) {
                $role = (string) (data_get($user, 'role') ?: 'user');
            }

            return [
                'userId' => (string) ($user?->getAuthIdentifier() ?? 'guest'),
                'role' => $role,
                'authChannel' => 'web',
            ];
        }

        return [
            'userId' => 'guest',
            'role' => 'guest',
            'authChannel' => 'guest',
        ];
    }
}
