<?php

use App\Http\Controllers\UserTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManageAccountController;
use App\Http\Controllers\AuthController;

Route::get("/user", function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");

Route::get("/hello-world", function () {
    return response()->json(["message" => "Hello, World!"]);
});

Route::get("/users", [UserTestController::class, "index"]);
Route::post("/users", [UserTestController::class, "store"]);

Route::get("/all-users", [UserController::class, "getAllUsers"]);

Route::post("/new/user", [ManageAccountController::class,"newUserAPI"]);


// ── Auth ──────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/login',  [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.jwt');
    Route::get('/me',      [AuthController::class, 'me'])->middleware('auth.jwt');
});

// ── Manage Accounts ───────────────────────────────────────────
Route::prefix('accounts')->middleware('auth.jwt')->group(function () {
    Route::get('/',        [ManageAccountController::class, 'index']);
});
Route::post('/new',       [ManageAccountController::class, 'newUserAPI']);
