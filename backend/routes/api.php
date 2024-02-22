<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;

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


// Route::resource('movie', MovieController::class)->except(['create', 'edit'])->middleware(['auth:sanctum']);
// Route::resource('movie', MovieController::class)->only(['create'])->middleware(['auth:sanctum']);

Route::group(['prefix' => 'notification', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [NotificationController::class, 'show']);
    Route::put('/update/{id}', [NotificationController::class, 'update']);
    Route::put('/update', [NotificationController::class, 'updateAll']);
});
