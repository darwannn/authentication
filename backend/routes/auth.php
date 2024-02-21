<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login'])->middleware(['guest']);
    // ->middleware('isAdmin')
    Route::post('/forgot-password', [UserController::class, 'forgot_password']);
    Route::put('/new-password/{token}/{email}', [UserController::class, 'new_password']);
    Route::get('/verify/{token}/{email}', [UserController::class, 'verify']);
    Route::put('/activate/{token}/{email}', [UserController::class, 'activate']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [UserController::class, 'logout']);
    });
});
