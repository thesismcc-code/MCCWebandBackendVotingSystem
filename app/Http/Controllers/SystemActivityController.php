<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemActivityController extends Controller
{
    public function index():View{
        return view('systemactivity');
    }
}
