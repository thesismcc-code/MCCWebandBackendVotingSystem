<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class ManageAccountController extends Controller
{
    public function index(): View
    {
        return view('manage-accounts');
    }
    public function storeNewAcction(Request $request){

        $messages = [
            'fullname.required' => 'Please provide the account holder\'s full name.',
            'email.required'    => 'We need an email address to send credentials.',
            'email.email'       => 'That email address doesn\'t look valid.',
            'role.requied'      => 'Please provide the account a roles.',
            'password.required' => 'Password is required.',
            'password.min:6'    => 'Password must be more than 6 characters.'
        ];

        $validator = Validator::make($request->all(), [
            'fullname'=>'required',
            'email'=>'required|email',
            'role' => 'required',
            'password' => 'required|min:6',
        ], $messages);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        return back()->with('success', 'SAO Head account has been created');
    }
}
