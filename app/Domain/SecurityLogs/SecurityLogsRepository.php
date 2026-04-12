<?php

namespace App\Domain\SecurityLogs;
use Illuminate\Pagination\LengthAwarePaginator;

interface SecurityLogsRepository
{
    public function getAllSecurityLogs(int $page = 1, string $search = '', string $course = '', string $yearLevel = '', int $perPage): LengthAwarePaginator;
    public function getCounts(): array;
    public function getUniqueCourses(): array;

}
