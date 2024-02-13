<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AccountController;

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

Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);
Route::post('/auth/forgot-password', [UserController::class, 'forgot_password']);
Route::put('/auth/new-password/{token}/{id}', [UserController::class, 'new_password']);
Route::get('/auth/verify/{token}/{id}', [UserController::class, 'verify']);
Route::put('/auth/activate/{token}/{id}', [UserController::class, 'activate']);
Route::get('/movie', [MovieController::class, 'index']);
Route::get('/movie/{id}', [MovieController::class, 'show']);
Route::get('/movie/search/{category}', [MovieController::class, 'search']);

// cron job
Route::delete('/tokens/delete', [UserController::class, 'delete_unused_tokens']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AccountController::class, 'me']);
    Route::post('/auth/logout', [UserController::class, 'logout']);
    Route::post('/movie', [MovieController::class, 'store']);
    Route::put('/movie/{id}', [MovieController::class, 'update']);
    Route::delete('/movie/{id}', [MovieController::class, 'destroy']);
});
