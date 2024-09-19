<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;



require __DIR__.'/auth.php';

Route::prefix('/v1')
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::apiResource('users', UserController::class)->only(['index']);
                Route::apiResource('tasks',TaskController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
                Route::apiResource('projects', ProjectController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

                Route::post('/projects/{project}/assign-user', [TeamController::class, 'assignUserToProject']);
                Route::delete('/projects/{project}/remove-user/{user}', [TeamController::class, 'removeUserFromProject']);

                Route::post('/tasks/{task}/notify', [NotificationController::class, 'sendTaskNotification']);
        });
});
