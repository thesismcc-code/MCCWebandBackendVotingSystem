<?php

namespace App\Eloquent\SystemActivity;

use App\Domain\SystemActivity\SystemActivity;
use App\Domain\SystemActivity\SystemActivityRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;

class EloquentSystemActivityRepository implements SystemActivityRepository
{
    private const SYSTEM_ACTIVITY_COLLECTION = 'system_activity';

    private const MAX_ROWS = 500;

    private Database $db;

    private Reference $systemActivityDB;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->systemActivityDB = $this->db->getReference(self::SYSTEM_ACTIVITY_COLLECTION);
    }

    public function getAllSystemActivities(): array
    {
        try {
            $snapshot = $this->systemActivityDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return [];
            }

            $value = $snapshot->getValue();
            if (! is_array($value)) {
                return [];
            }

            return collect($value)
                ->filter(fn ($item) => is_array($item))
                ->map(fn (array $item, string $key) => SystemActivity::fromFirebase(array_merge($item, ['id' => $key])))
                ->sortByDesc(fn (SystemActivity $log) => $log->getCreatedAt())
                ->take(self::MAX_ROWS)
                ->values()
                ->all();
        } catch (\Throwable $e) {
            Log::error('EloquentSystemActivityRepository::getAllSystemActivities — '.$e->getMessage());

            return [];
        }
    }

    public function getErrorLogsSince(string $sinceIso, string $userFilter = 'all', string $dateFilter = 'all'): array
    {
        try {
            $all = $this->getAllSystemActivities();
            $filtered = $this->applyRoleAndDateFilters(
                collect($all)
                    ->filter(fn (SystemActivity $a) => in_array($a->getLevel(), ['warning', 'error', 'critical'], true))
                    ->sortByDesc(fn (SystemActivity $a) => $a->getCreatedAt())
                    ->values(),
                $userFilter,
                $dateFilter,
            );

            if ($sinceIso === '') {
                return $filtered->take(5)->all();
            }

            try {
                $since = Carbon::parse($sinceIso);
            } catch (\Throwable) {
                return [];
            }

            return $filtered
                ->filter(function (SystemActivity $a) use ($since) {
                    try {
                        return Carbon::parse($a->getCreatedAt())->greaterThan($since);
                    } catch (\Throwable) {
                        return false;
                    }
                })
                ->values()
                ->all();
        } catch (\Throwable $e) {
            Log::error('EloquentSystemActivityRepository::getErrorLogsSince — '.$e->getMessage());

            return [];
        }
    }

    public function paginateByLevelGroup(
        int $page,
        int $perPage,
        string $levelGroup,
        string $userFilter = 'all',
        string $dateFilter = 'all'
    ): LengthAwarePaginator {
        $pageName = match ($levelGroup) {
            'realtime' => 'realtime_page',
            'error' => 'error_page',
            default => 'page',
        };

        try {
            $all = $this->getAllSystemActivities();
            $filtered = collect($all)->filter(function (SystemActivity $a) use ($levelGroup) {
                return match ($levelGroup) {
                    'realtime' => $a->getLevel() === 'info',
                    'error' => in_array($a->getLevel(), ['warning', 'error', 'critical'], true),
                    default => false,
                };
            })->values();
            $filtered = $this->applyRoleAndDateFilters($filtered, $userFilter, $dateFilter);

            $slice = $filtered->slice(($page - 1) * $perPage, $perPage)->values();

            return new LengthAwarePaginator(
                items: $slice,
                total: $filtered->count(),
                perPage: $perPage,
                currentPage: $page,
                options: [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => request()->query(),
                    'pageName' => $pageName,
                ],
            );
        } catch (\Throwable $e) {
            Log::error('EloquentSystemActivityRepository::paginateByLevelGroup — '.$e->getMessage());

            return new LengthAwarePaginator(
                items: collect(),
                total: 0,
                perPage: $perPage,
                currentPage: 1,
                options: [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => request()->query(),
                    'pageName' => $pageName,
                ],
            );
        }
    }

    public function getSystemActivityById(string $id): SystemActivity
    {
        try {
            $snapshot = $this->systemActivityDB->getChild($id)->getSnapshot();

            if (! $snapshot->exists() || ! is_array($snapshot->getValue())) {
                throw new \InvalidArgumentException("System activity not found: {$id}");
            }

            return SystemActivity::fromFirebase(array_merge($snapshot->getValue(), ['id' => $id]));
        } catch (\Throwable $e) {
            Log::error('EloquentSystemActivityRepository::getSystemActivityById — '.$e->getMessage());
            throw $e;
        }
    }

    public function createSystemActivity(SystemActivity $systemActivity): SystemActivity
    {
        try {
            $payload = $systemActivity->toFirebasePayload();
            $newRef = $this->systemActivityDB->push($payload);
            $key = $newRef->getKey();

            return SystemActivity::fromFirebase(array_merge($payload, ['id' => $key]));
        } catch (\Throwable $e) {
            Log::error('EloquentSystemActivityRepository::createSystemActivity — '.$e->getMessage());
            throw $e;
        }
    }

    public function updateSystemActivity(string $id, SystemActivity $systemActivity): SystemActivity
    {
        try {
            $payload = $systemActivity->toFirebasePayload();
            $this->systemActivityDB->getChild($id)->set($payload);

            return SystemActivity::fromFirebase(array_merge($payload, ['id' => $id]));
        } catch (\Throwable $e) {
            Log::error('EloquentSystemActivityRepository::updateSystemActivity — '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteSystemActivity(string $id): void
    {
        try {
            $this->systemActivityDB->getChild($id)->remove();
        } catch (\Throwable $e) {
            Log::error('EloquentSystemActivityRepository::deleteSystemActivity — '.$e->getMessage());
            throw $e;
        }
    }

    private function applyRoleAndDateFilters(
        \Illuminate\Support\Collection $logs,
        string $userFilter,
        string $dateFilter
    ): \Illuminate\Support\Collection {
        $normalizedUserFilter = strtolower($userFilter);
        $normalizedDateFilter = strtolower($dateFilter);
        $allowedUserFilters = ['all', 'admin', 'student', 'comelec', 'sao'];
        $allowedDateFilters = ['all', 'today', 'yesterday', 'last_week'];

        if (! in_array($normalizedUserFilter, $allowedUserFilters, true)) {
            $normalizedUserFilter = 'all';
        }

        if (! in_array($normalizedDateFilter, $allowedDateFilters, true)) {
            $normalizedDateFilter = 'all';
        }

        if ($normalizedUserFilter !== 'all') {
            $logs = $logs->filter(function (SystemActivity $activity) use ($normalizedUserFilter) {
                return strtolower($activity->getRole()) === $normalizedUserFilter;
            })->values();
        }

        if ($normalizedDateFilter === 'all') {
            return $logs->values();
        }

        $dateRange = $this->resolveDateRange($normalizedDateFilter);
        if ($dateRange === null) {
            return $logs->values();
        }

        return $logs->filter(function (SystemActivity $activity) use ($dateRange) {
            try {
                $createdAt = Carbon::parse($activity->getCreatedAt());

                return $createdAt->between($dateRange['start'], $dateRange['end']);
            } catch (\Throwable) {
                return false;
            }
        })->values();
    }

    /**
     * @return array{start: Carbon, end: Carbon}|null
     */
    private function resolveDateRange(string $dateFilter): ?array
    {
        $now = now();

        return match ($dateFilter) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'yesterday' => [
                'start' => $now->copy()->subDay()->startOfDay(),
                'end' => $now->copy()->subDay()->endOfDay(),
            ],
            'last_week' => [
                'start' => $now->copy()->subDays(6)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            default => null,
        };
    }
}
