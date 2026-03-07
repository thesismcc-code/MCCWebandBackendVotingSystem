<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentEligibilityController extends Controller
{
    public function index(): View
    {
        return view('studeneligibility');
    }
}
