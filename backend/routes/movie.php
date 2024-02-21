
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::group(['prefix' => 'movie'], function () {
    Route::get('/', [MovieController::class, 'index']);
    Route::get('/{id}', [MovieController::class, 'show']);
    Route::get('/search/{title}', [MovieController::class, 'search']);
    ///'can:admin-only'
    Route::group(['middleware' => ['auth:sanctum',]], function () {
        Route::post('/', [MovieController::class, 'store']);
        Route::put('/{id}', [MovieController::class, 'update']);
        Route::delete('/{id}', [MovieController::class, 'destroy']);
    });
});
