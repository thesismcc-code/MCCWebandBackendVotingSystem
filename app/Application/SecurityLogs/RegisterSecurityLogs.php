<?php

namespace App\Application\SecurityLogs;

use App\Domain\SecurityLogs\SecurityLogsRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class RegisterSecurityLogs
{

    private SecurityLogsRepository $securityLogsRepository;

    public function __construct(SecurityLogsRepository $securityLogsRepository)
    {
        $this->securityLogsRepository = $securityLogsRepository;
    }

    public function getAllSecurityLogs(int $page = 1, string $search = '', string $course = '', string $yearLevel = '', int $perPage): LengthAwarePaginator
    {
        return $this->securityLogsRepository->getAllSecurityLogs($page, $search, $course, $yearLevel, $perPage);
    }
    public function getCounts(): array
    {
        return $this->securityLogsRepository->getCounts();
    }
    public function getUniqueCourses(): array
    {
        return $this->securityLogsRepository->getUniqueCourses();
    }
}
