<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\HeadingController;

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
Route::middleware('auth:api')->group(function () {
    Route::prefix('articles')->group(function () {
        Route::get('', [ArticleController::class, 'index']);
        Route::get('{article}', [ArticleController::class, 'show']);
    });

    Route::prefix('authors')->group(function () {
        Route::get('', [AuthorController::class, 'index']);
        Route::get('{author}', [AuthorController::class, 'show']);
    });

    Route::prefix('headings')->group(function () {
        Route::get('', [HeadingController::class, 'index']);
    });
});
