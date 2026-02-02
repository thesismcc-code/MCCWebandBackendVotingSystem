<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuickAccessController;

Route::get('/', [AuthController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('view.dashboard');
Route::get('/quick-access', [QuickAccessController::class, 'index'])->name('view.quick-access');
