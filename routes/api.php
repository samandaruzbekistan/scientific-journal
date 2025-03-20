<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
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
Route::get('user/articles/{id}', [ArticleController::class, 'get_articles']);

Route::get('get-active-journal',[JournalController::class, 'get_active_journal']);


Route::post('store-article',[ArticleController::class, 'store']);
Route::get('get-article-types', [ArticleTypeController::class, 'index']);
Route::post('send-to-review', [ArticleController::class, 'send_to_review']);

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

    Route::post('editorial-add-to-team', [EditorialsTeamController::class, 'add_editorial']);
    Route::post('editorial-delete-from-team', [EditorialsTeamController::class, 'delete_editorial']);

});

Route::prefix('editorial')->group(function () {
    Route::post('login', [EditorialController::class, 'login']);
    Route::post('review-articles', [EditorialController::class, 'get_review_articles']);
    Route::get('article/{id}', [EditorialController::class, 'get_article']);
    Route::post('send-article-to-editorial', [EditorialController::class, 'send_to_editorial']);
    Route::post('get-votes', [EditorialController::class, 'get_publish_votes']);
    Route::post('vote', [EditorialController::class, 'vote']);

});






