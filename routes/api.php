<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArticleController;

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

Route::prefix('articles')->group(function () {
    Route::get('', [ArticleController::class, 'index']);
    Route::get('{article_id}', [ArticleController::class, 'show']);
});
