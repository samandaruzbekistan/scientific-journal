<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\EditorialsTeamController;
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

Route::get('get-active-journal',[JournalController::class, 'get_active_journal']);


Route::post('store-article',[\App\Http\Controllers\ArticleController::class, 'store']);
Route::get('get-article-types', [ArticleTypeController::class, 'index']);


Route::apiResource('authors', AuthorController::class);

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);

    Route::apiResource('article-types', ArticleTypeController::class);

    Route::get('get-article-prices', [ArticleTypeController::class, 'get_article_prices']);
    Route::post('create-article-price', [AdminController::class, 'create_article_price']);

    Route::get('get/{id}', [AdminController::class, 'show']);
    Route::apiResource('journals', JournalController::class);
    Route::get('change-journal-status/{id}', [JournalController::class, 'change_status']);

    Route::apiResource('editorials', EditorialController::class);
    Route::apiResource('editorial-teams', EditorialsTeamController::class);

    Route::post('add-editorial', [EditorialsTeamController::class, 'add_editorial']);
});






