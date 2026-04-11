<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SecurityLogsController extends Controller
{
    public function index(Request $request): View
    {
        $search        = $request->get('search', '');
        $courseFilter  = $request->get('course', '');
        $yearFilter    = $request->get('year_level', '');

        $allLogs = collect([
            [
                'student_id'     => 'CS-2025-001',
                'first_name'     => 'Jose',
                'last_name'      => 'Perolino',
                'course'         => 'Computer Science',
                'year_level'     => '4th Year',
                'first_attempt'  => '2025-12-05 10:43:00',
                'second_attempt' => '2025-12-05 10:43:00',
                'status'         => 'blocked',
                'log_type'       => 'duplicate_vote',
            ],
            [
                'student_id'     => 'IT-2025-035',
                'first_name'     => 'Myles',
                'last_name'      => 'Macrohon',
                'course'         => 'Information Technology',
                'year_level'     => '2nd Year',
                'first_attempt'  => '2025-12-05 13:30:00',
                'second_attempt' => '2025-12-05 13:30:00',
                'status'         => 'blocked',
                'log_type'       => 'duplicate_vote',
            ],
            [
                'student_id'     => 'BA-2025-141',
                'first_name'     => 'Honey',
                'last_name'      => 'Malang',
                'course'         => 'Business Administration',
                'year_level'     => '3rd Year',
                'first_attempt'  => '2025-12-05 08:41:00',
                'second_attempt' => '2025-12-05 08:41:00',
                'status'         => 'blocked',
                'log_type'       => 'rejected_fingerprint',
            ],
            [
                'student_id'     => 'CS-2025-225',
                'first_name'     => 'Jahaira',
                'last_name'      => 'Ampaso',
                'course'         => 'Business Administration',
                'year_level'     => '1st Year',
                'first_attempt'  => '2025-12-05 10:43:00',
                'second_attempt' => '2025-12-05 10:43:00',
                'status'         => 'blocked',
                'log_type'       => 'denied_access',
            ],
        ]);

        $filtered = $allLogs
            ->when($search, fn($col) => $col->filter(
                fn($row) =>
                str_contains(strtolower($row['student_id']), strtolower($search)) ||
                    str_contains(strtolower($row['first_name'] . ' ' . $row['last_name']), strtolower($search))
            ))
            ->when($courseFilter, fn($col) => $col->filter(
                fn($row) =>
                $row['course'] === $courseFilter
            ))
            ->when($yearFilter, fn($col) => $col->filter(
                fn($row) =>
                $row['year_level'] === $yearFilter
            ));

        $counts = [
            'duplicate_votes'       => $allLogs->where('log_type', 'duplicate_vote')->count(),
            'rejected_fingerprints' => $allLogs->where('log_type', 'rejected_fingerprint')->count(),
            'denied_access'         => $allLogs->where('log_type', 'denied_access')->count(),
        ];

        $courses = $allLogs->pluck('course')->unique()->sort()->values()->toArray();

        $perPage     = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedItems  = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $logs = new LengthAwarePaginator(
            $pagedItems,
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $logs->getCollection()->transform(fn($item) => (object) $item);

        return view('securitylogs', compact('logs', 'counts', 'courses'));
    }
}
