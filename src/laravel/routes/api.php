<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectsController;
use App\Http\Controllers\Api\V1\TextsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::middleware('auth:sanctum')
        ->apiResource('projects', ProjectsController::class)
        ->names('api.projects');
    Route::middleware('auth:sanctum')
        ->apiResource('project/{project_id}/texts', TextsController::class)
        ->names('api.projects.texts');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
});
