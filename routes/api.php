<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register',[AuthController::class, 'register']);
Route::post('verify-email', [AuthController::class, 'verify_email']);
Route::post('resend-verification', [AuthController::class, 'resend_email_verification']);
Route::post('login',[AuthController::class, 'login']);
Route::get('get-academic-degrees', [AuthController::class, 'get_academic_degrees']);
Route::get('users/{id?}', [UserController::class, 'index']);
Route::post('user-update',[AuthController::class, 'update']);

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);

    Route::get('get/{id}', [AdminController::class, 'show']);
    Route::apiResource('journals', JournalController::class);
});






