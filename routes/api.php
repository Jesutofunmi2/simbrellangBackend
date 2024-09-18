<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



require __DIR__.'/auth.php';

Route::prefix('/v1')
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::apiResource('users', UserController::class);
        });
});
