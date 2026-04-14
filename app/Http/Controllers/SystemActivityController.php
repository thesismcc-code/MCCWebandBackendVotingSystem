<?php

namespace App\Http\Controllers;

use App\Application\SystemActivity\RegisterSystemActivity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemActivityController extends Controller
{
    public function __construct(
        private RegisterSystemActivity $registerSystemActivity
    ) {}

    public function index(Request $request): View
    {
        $realtimePage = max(1, (int) $request->query('realtime_page', 1));
        $errorPage = max(1, (int) $request->query('error_page', 1));

        $realtimeLogs = $this->registerSystemActivity->paginateRealtimeLogs($realtimePage)->withQueryString();
        $errorLogs = $this->registerSystemActivity->paginateErrorLogs($errorPage)->withQueryString();

        return view('systemactivity', compact('realtimeLogs', 'errorLogs'));
    }
}
