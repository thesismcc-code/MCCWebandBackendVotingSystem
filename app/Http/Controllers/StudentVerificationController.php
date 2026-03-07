<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentVerificationController extends Controller
{
    public function index(): View
    {
        return view('student.verify');
    }

    public function validateVerify(Request $request): View|RedirectResponse
    {
        return redirect()->route('view.student-dashboard');
    }
}
