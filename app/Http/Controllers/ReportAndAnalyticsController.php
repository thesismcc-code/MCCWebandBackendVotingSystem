<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportAndAnalyticsController extends Controller
{
    public function index():View{
        return view('report');
    }

    public function indexEndOfElection():View{
        return view('endofelection');
    }
}
