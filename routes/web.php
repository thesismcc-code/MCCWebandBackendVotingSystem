<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuickAccessController;
use App\Http\Controllers\ManageAccountController;
use App\Http\Controllers\FingerPrintController;
use App\Http\Controllers\VotingLogsController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\SystemActivityController;
use App\Http\Controllers\ReportAndAnalyticsController;
use App\Http\Controllers\SAODashboardController;

Route::get('/', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('view.dashboard');
Route::get('/quick-access', [QuickAccessController::class, 'index'])->name('view.quick-access');
Route::get('/manage-accounts', [ManageAccountController::class, 'index'])->name('view.manage-accounts');
Route::post('/store-new-accounts', [ManageAccountController::class, 'storeNewAcction'])->name('store.new-accounts');
Route::get('/finger-print', [FingerPrintController::class, 'index'])->name('view.finger-print');
Route::get('/voting-logs', [VotingLogsController::class, 'index'])->name('view.voting-logs');
Route::get('/election-control', [ElectionController::class, 'index'])->name('view.election-control');
Route::get('/election-control-posistion-setup', [ElectionController::class, 'indexPosistionSetup'])->name('view.election-control-posistion-setup');
Route::get('/election-control-candidate-list', [ElectionController::class, 'indexCandidateList'])->name('view.election-control-candidate-list');
Route::get('/system-activity', [SystemActivityController::class, 'index'])->name('view.system-activity');
Route::get('/reports-and-analytics', [ReportAndAnalyticsController::class, 'index'])->name('view.reports-and-analytics');
Route::get('/reports-and-analytics-end-of-election', [ReportAndAnalyticsController::class, 'indexEndOfElection'])->name('view.reports-and-analytics-end-of-election');

// Sao Dashboard
Route::get('/sao-dashboard', [SAODashboardController::class, 'index'])->name('view.sao-dashboard');
