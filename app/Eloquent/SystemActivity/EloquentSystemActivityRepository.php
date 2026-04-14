<?php

namespace App\Eloquent\SystemActivity;

use App\Domain\SystemActivity\SystemActivity;
use App\Domain\SystemActivity\SystemActivityRepository;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function paginateByLevelGroup(int $page, int $perPage, string $levelGroup): LengthAwarePaginator
    {
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
}
