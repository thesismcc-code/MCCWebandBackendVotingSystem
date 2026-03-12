<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors($validator);
        }
        return redirect()->route('view.dashboard');
    }
}
