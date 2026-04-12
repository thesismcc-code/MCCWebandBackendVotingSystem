<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Application\SecurityLogs\RegisterSecurityLogs;


class SecurityLogsController extends Controller
{
    private RegisterSecurityLogs $registerSecurityLogs;
    public function __construct(RegisterSecurityLogs $registerSecurityLogs)
    {
        $this->registerSecurityLogs = $registerSecurityLogs;
    }
    public function index(Request $request): View
    {
        $logs = $this->registerSecurityLogs->getAllSecurityLogs(
            page: $request->integer('page', 1),
            search: $request->get('search', ''),
            course: $request->get('course', ''),
            yearLevel: $request->get('year_level', ''),
            perPage: 5,
        );
        $counts  = $this->registerSecurityLogs->getCounts();
        $courses = $this->registerSecurityLogs->getUniqueCourses();

        return view('securitylogs', compact('logs', 'counts', 'courses'));
    }
}
