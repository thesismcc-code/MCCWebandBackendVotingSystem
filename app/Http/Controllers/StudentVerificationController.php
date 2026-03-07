<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentVerificationController extends Controller
{
    public function index(): View
    {
        return view('student.verify');
    }
}
