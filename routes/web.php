<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuickAccessController;
use App\Http\Controllers\ManageAccountController;
use App\Http\Controllers\FingerPrintController;
use App\Http\Controllers\VotingLogsController;
use App\Http\Controllers\ElectionController;

Route::get('/', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('view.dashboard');
Route::get('/quick-access', [QuickAccessController::class, 'index'])->name('view.quick-access');
Route::get('/manage-accounts', [ManageAccountController::class, 'index'])->name('view.manage-accounts');
Route::post('/store-new-accounts', [ManageAccountController::class, 'storeNewAcction'])->name('store.new-accounts');
Route::get('/finger-print', [FingerPrintController::class, 'index'])->name('view.finger-print');
Route::get('/voting-logs', [VotingLogsController::class, 'index'])->name('view.voting-logs');
Route::get('/election-control', [ElectionController::class, 'index'])->name('view.election-controll');
