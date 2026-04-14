<?php

namespace App\Domain\SystemActivity;

use Illuminate\Pagination\LengthAwarePaginator;

interface SystemActivityRepository
{
    public function getAllSystemActivities(): array;

    /**
     * Error-group logs (warning, error, critical). When {@see $sinceIso} is empty, returns the five newest
     * for bootstrap polling; otherwise returns entries with created_at strictly after {@see $sinceIso}.
     *
     * @return array<int, SystemActivity>
     */
    public function getErrorLogsSince(string $sinceIso): array;

    /**
     * @param  'realtime'|'error'  $levelGroup
     */
    public function paginateByLevelGroup(int $page, int $perPage, string $levelGroup): LengthAwarePaginator;

    public function getSystemActivityById(string $id): SystemActivity;

    public function createSystemActivity(SystemActivity $systemActivity): SystemActivity;

    public function updateSystemActivity(string $id, SystemActivity $systemActivity): SystemActivity;

    public function deleteSystemActivity(string $id): void;
}
