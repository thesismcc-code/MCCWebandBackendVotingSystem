<?php

namespace App\Eloquent\SecurityLogs;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domain\SecurityLogs\SecurityLogsRepository;
use App\Domain\SecurityLogs\SecurityLogs;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EloquentSecurityLogsRepository implements SecurityLogsRepository
{
    private Database $db;
    private Reference $securityLogsDB;
    private const SECURITY_COLLECTION = 'security_logs';

    public function __construct(Database $database)
    {
        $this->db             = $database;
        $this->securityLogsDB = $this->db->getReference(self::SECURITY_COLLECTION);
    }

    public function getAllSecurityLogs(
        int     $page        = 1,
        string  $search      = '',
        string  $course      = '',
        string  $yearLevel   = '',
        int     $perPage
    ): LengthAwarePaginator {
        try {
            $snapshot = $this->securityLogsDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return $this->emptyPaginator($page, $perPage);
            }

            $logs = collect($snapshot->getValue())
                ->filter(fn($item) => is_array($item))           // skip malformed nodes
                ->map(fn($item) => SecurityLogs::fromFirebase($item))
                ->when($search, fn(Collection $col) => $col->filter(
                    fn(SecurityLogs $log) =>
                        str_contains(strtolower($log->getStudentId()), strtolower($search)) ||
                        str_contains(strtolower($log->getFullName()), strtolower($search))
                ))
                ->when($course, fn(Collection $col) => $col->filter(
                    fn(SecurityLogs $log) => $log->getCourse() === $course
                ))
                ->when($yearLevel, fn(Collection $col) => $col->filter(
                    fn(SecurityLogs $log) => $log->getYearLevel() === $yearLevel
                ))
                ->sortByDesc(fn(SecurityLogs $log) => $log->getCreatedAt())
                ->values();

            return $this->paginate($logs, $page, $perPage);

        } catch (\Throwable $e) {
            Log::error('EloquentSecurityLogsRepository::getAllSecurityLogs — ' . $e->getMessage());
            return $this->emptyPaginator($page, $perPage);
        }
    }

    public function getCounts(): array
    {
        try {
            $snapshot = $this->securityLogsDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return $this->zeroCounts();
            }

            $logs = collect($snapshot->getValue())
                ->filter(fn($item) => is_array($item))
                ->map(fn($item) => SecurityLogs::fromFirebase($item));

            return [
                'duplicate_votes'       => $logs->filter(fn(SecurityLogs $l) => $l->isDuplicateVote())->count(),
                'rejected_fingerprints' => $logs->filter(fn(SecurityLogs $l) => $l->isRejectedFingerprint())->count(),
                'denied_access'         => $logs->filter(fn(SecurityLogs $l) => $l->isDeniedAccess())->count(),
            ];

        } catch (\Throwable $e) {
            Log::error('EloquentSecurityLogsRepository::getCounts — ' . $e->getMessage());
            return $this->zeroCounts();
        }
    }

    public function getUniqueCourses(): array
    {
        try {
            $snapshot = $this->securityLogsDB->getSnapshot();

            if (! $snapshot->exists() || $snapshot->getValue() === null) {
                return [];
            }

            return collect($snapshot->getValue())
                ->filter(fn($item) => is_array($item))
                ->map(fn($item) => $item['course'] ?? '')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray();

        } catch (\Throwable $e) {
            Log::error('EloquentSecurityLogsRepository::getUniqueCourses — ' . $e->getMessage());
            return [];
        }
    }
    private function paginate(Collection $items, int $page, int $perPage): LengthAwarePaginator
    {
        $total  = $items->count();
        $sliced = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            items:       $sliced,
            total:       $total,
            perPage:     $perPage,
            currentPage: $page,
            options:     [
                'path'  => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ],
        );
    }

    private function emptyPaginator(int $page, int $perPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator([], 0, $perPage, $page);
    }

    private function zeroCounts(): array
    {
        return [
            'duplicate_votes'       => 0,
            'rejected_fingerprints' => 0,
            'denied_access'         => 0,
        ];
    }
}
