<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComelecDashboarController;
use App\Http\Controllers\ComelectManageCandidate;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\FingerPrintController;
use App\Http\Controllers\ManageAccountController;
use App\Http\Controllers\QuickAccessController;
use App\Http\Controllers\ReportAndAnalyticsController;
use App\Http\Controllers\SAOCandidateList;
use App\Http\Controllers\SAODashboardController;
use App\Http\Controllers\SAOFinalResult;
use App\Http\Controllers\SAOVoterParticipationController;
use App\Http\Controllers\SecurityLogsController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentEligibilityController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentTutorialController;
use App\Http\Controllers\StudentVerificationController;
use App\Http\Controllers\StudentVoteTutorialController;
use App\Http\Controllers\SystemActivityController;
use App\Http\Controllers\VotingLogsController;
use Illuminate\Support\Facades\Route;

// ── Public routes ─────────────────────────────────────────────
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/students', [AuthController::class, 'studentIndex'])->name('view.student');
Route::post('/validate-login', [AuthController::class, 'studentLogin'])->name('validate-login');
Route::get('/students-tutorials', [StudentTutorialController::class, 'index'])->name('view.student-tutorials');
Route::get('/students-how-to-vote', [StudentVoteTutorialController::class, 'index'])->name('view.students-how-to-vote');

// ── Admin routes ──────────────────────────────────────────────
Route::middleware('auth.session')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('view.dashboard');
    Route::get('/quick-access', [QuickAccessController::class, 'index'])->name('view.quick-access');
    Route::get('/manage-accounts', [ManageAccountController::class, 'index'])->name('view.manage-accounts');
    Route::post('/store-new-accounts', [ManageAccountController::class, 'newUser'])->name('store.new-accounts');
    Route::post('/delete-user', [ManageAccountController::class, 'deleteUser'])->name('delete-user');
    Route::post('/update-user', [ManageAccountController::class, 'updateUser'])->name('update-user');
    Route::get('/finger-print', [FingerPrintController::class, 'index'])->name('view.finger-print');
    Route::post('/finger-print/student/update', [FingerPrintController::class, 'updateStudent'])->name('finger-print.student.update');
    Route::post('/finger-print/student/delete', [FingerPrintController::class, 'deleteStudent'])->name('finger-print.student.delete');
    Route::get('/voting-logs', [VotingLogsController::class, 'index'])->name('view.voting-logs');
    Route::get('/voting-logs/export-pdf', [VotingLogsController::class, 'exportPdf'])->name('voting-logs.export-pdf');
    Route::get('/security-logs', [SecurityLogsController::class, 'index'])->name('view.security-logs');
    Route::get('/election-control', [ElectionController::class, 'index'])->name('view.election-control');
    Route::get('/election-control-posistion-setup', [ElectionController::class, 'indexPositionSetup'])->name('view.election-control-posistion-setup');
    Route::get('/election-control-candidate-list', [ElectionController::class, 'indexCandidateList'])->name('view.election-control-candidate-list');
    Route::post('/election-control/save-general', [ElectionController::class, 'saveGeneralSettings'])->name('election.save-general');
    Route::post('/election-control/save-schedule', [ElectionController::class, 'saveScheduleSettings'])->name('election.save-schedule');
    Route::post('/election-control/position/save', [ElectionController::class, 'savePosition'])->name('election.position.save');
    Route::post('/election-control/position/update', [ElectionController::class, 'updatePosition'])->name('election.position.update');
    Route::post('/election-control/position/delete', [ElectionController::class, 'deletePosition'])->name('election.position.delete');
    Route::post('/election-control/candidate/save', [ElectionController::class, 'saveCandidate'])->name('election.candidate.save');
    Route::post('/election-control/candidate/update', [ElectionController::class, 'updateCandidate'])->name('election.candidate.update');
    Route::post('/election-control/candidate/delete', [ElectionController::class, 'deleteCandidate'])->name('election.candidate.delete');
    Route::get('/system-activity', [SystemActivityController::class, 'index'])->name('view.system-activity');
    Route::get('/system-activity/recent-errors', [SystemActivityController::class, 'recentErrorsJson'])->name('view.system-activity.recent-errors');
    Route::get('/reports-and-analytics', [ReportAndAnalyticsController::class, 'index'])->name('view.reports-and-analytics');
    Route::get('/reports-and-analytics/live-data', [ReportAndAnalyticsController::class, 'liveData'])->name('reports.live-data');
    Route::get('/reports-and-analytics-end-of-election', [ReportAndAnalyticsController::class, 'indexEndOfElection'])->name('view.reports-and-analytics-end-of-election');
    Route::get('/reports-and-analytics/end-of-election/pdf', [ReportAndAnalyticsController::class, 'exportEndOfElectionPdf'])->name('reports.end-of-election-pdf');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ── Comelec routes ──────────────────────────────────────
Route::middleware('auth.session')->group(function () {
    Route::get('/sao-dashboard', [SAODashboardController::class, 'index'])->name('view.sao-dashboard');
    Route::get('/sao-candidate-list', [SAOCandidateList::class, 'index'])->name('view.sao-candidate-list');
    Route::get('/sao-voter-participation', [SAOVoterParticipationController::class, 'index'])->name('view.sao-voter-participation');
    Route::get('/sao-final-results', [SAOFinalResult::class, 'index'])->name('view.sao-final-results');
    Route::get('/student-eligibility', [StudentEligibilityController::class, 'index'])->name('view.student-eligibility');
});

// ── Comelec routes ──────────────────────────────────────
Route::middleware('auth.session')->group(function () {
    Route::get('/comelec-dashboard', [ComelecDashboarController::class, 'index'])->name('view.comelec-dashboard');
    Route::get('/comelec-manage-candidates', [ComelectManageCandidate::class, 'index'])->name('view.comelec-manage-candidates');
});

// ── Student routes ────────────────────────────────────────────
Route::middleware('auth.session')->group(function () {
    Route::post('/student-validate-login', [AuthController::class, 'studentLogin'])->name('student.login');
    Route::post('/student-logout', [AuthController::class, 'logoutStudent'])->name('student.logout');
    Route::get('/students-dashboard', [StudentDashboardController::class, 'index'])->name('view.student-dashboard');
    Route::get('/students-profile', [StudentProfileController::class, 'index'])->name('view.student-profile');
    Route::post('/students-update-profile', [StudentProfileController::class, 'updateProfile'])->name('update-profile');
    Route::get('/students-verification', [StudentVerificationController::class, 'index'])->name('view.student-verification');
    Route::post('/students-validate-verification', [StudentVerificationController::class, 'validateVerify'])->name('student-validateß-verification');
});
