<?php

namespace App\Domain\SystemActivity;

use Illuminate\Pagination\LengthAwarePaginator;

interface SystemActivityRepository
{
    public function getAllSystemActivities(): array;

    /**
     * @param  'realtime'|'error'  $levelGroup
     */
    public function paginateByLevelGroup(int $page, int $perPage, string $levelGroup): LengthAwarePaginator;

    public function getSystemActivityById(string $id): SystemActivity;

    public function createSystemActivity(SystemActivity $systemActivity): SystemActivity;

    public function updateSystemActivity(string $id, SystemActivity $systemActivity): SystemActivity;

    public function deleteSystemActivity(string $id): void;
}
