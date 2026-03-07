<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentProfileController extends Controller
{
    public function index(): View
    {
        return view('student.profile');
    }
    public function updateProfile(Request $request): View|RedirectResponse
    {
        return redirect()->route('view.student-verification');
    }
}
