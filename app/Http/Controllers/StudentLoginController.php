<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class StudentLoginController extends Controller
{
    public function index(): View
    {
        return view('student.loginpage');
    }

    public function validateLogin(Request $request): View|RedirectResponse
    {
        $data = $request->all();

        // $validator = Validator::make($data, [
        //     'student_type' => 'required',
        //     'student_id'   => 'required',
        //     'password'     => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        if ($data['student_type'] === 'Old Student') {
            // Old students go straight to dashboard
            return redirect()->route('view.student-dashboard');
        }

        // New students → profile update → verification → dashboard
        return redirect()->route('view.student-profile');
    }
}
