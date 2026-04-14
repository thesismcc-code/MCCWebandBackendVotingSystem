<?php

namespace App\Http\Controllers;

use App\Application\SystemActivity\RegisterSystemActivity;
use App\Domain\SystemActivity\SystemActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemActivityController extends Controller
{
    private const ALLOWED_TABS = ['realtime', 'error'];

    private const ALLOWED_USER_FILTERS = ['all', 'admin', 'student', 'comelec', 'sao'];

    private const ALLOWED_DATE_FILTERS = ['all', 'today', 'yesterday', 'last_week'];

    public function __construct(
        private RegisterSystemActivity $registerSystemActivity
    ) {}

    public function index(Request $request): View
    {
        $realtimePage = max(1, (int) $request->query('realtime_page', 1));
        $errorPage = max(1, (int) $request->query('error_page', 1));
        $activeTab = $this->normalizeFilter(
            (string) $request->query('active_tab', 'realtime'),
            self::ALLOWED_TABS,
            'realtime',
        );
        $userFilter = $this->normalizeFilter(
            (string) $request->query('user_filter', 'all'),
            self::ALLOWED_USER_FILTERS,
            'all',
        );
        $dateFilter = $this->normalizeFilter(
            (string) $request->query('date_filter', 'all'),
            self::ALLOWED_DATE_FILTERS,
            'all',
        );

        $realtimeUserFilter = $activeTab === 'realtime' ? $userFilter : 'all';
        $realtimeDateFilter = $activeTab === 'realtime' ? $dateFilter : 'all';
        $errorUserFilter = $activeTab === 'error' ? $userFilter : 'all';
        $errorDateFilter = $activeTab === 'error' ? $dateFilter : 'all';

        $realtimeLogs = $this->registerSystemActivity
            ->paginateRealtimeLogs($realtimePage, $realtimeUserFilter, $realtimeDateFilter)
            ->withQueryString();
        $errorLogs = $this->registerSystemActivity
            ->paginateErrorLogs($errorPage, $errorUserFilter, $errorDateFilter)
            ->withQueryString();

        return view('systemactivity', compact(
            'realtimeLogs',
            'errorLogs',
            'activeTab',
            'userFilter',
            'dateFilter',
        ));
    }

    public function recentErrorsJson(Request $request): JsonResponse
    {
        $since = (string) $request->query('since', '');
        $userFilter = $this->normalizeFilter(
            (string) $request->query('user_filter', 'all'),
            self::ALLOWED_USER_FILTERS,
            'all',
        );
        $dateFilter = $this->normalizeFilter(
            (string) $request->query('date_filter', 'all'),
            self::ALLOWED_DATE_FILTERS,
            'all',
        );
        $logs = $this->registerSystemActivity->getErrorLogsSince($since, $userFilter, $dateFilter);

        return response()->json([
            'data' => array_map(fn (SystemActivity $log) => $log->toArray(), $logs),
        ]);
    }

    /**
     * @param  array<int, string>  $allowed
     */
    private function normalizeFilter(string $value, array $allowed, string $fallback): string
    {
        $normalized = strtolower(trim($value));

        if (! in_array($normalized, $allowed, true)) {
            return $fallback;
        }

        return $normalized;
    }
}
