
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::group(['prefix' => 'account', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AccountController::class, 'me']);
});
