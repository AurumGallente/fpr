<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectsController;
use App\Http\Controllers\Api\V1\TextsController;
use App\Http\Controllers\Api\V1\LanguageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::name('api.')->middleware('auth:sanctum')->group(function () {
        Route::apiResource('projects', ProjectsController::class);
        Route::apiResource('projects.texts', TextsController::class);
        Route::post('texts/search', [TextsController::class, 'search'])->name('texts.search');
        Route::apiresource('languages', LanguageController::class)->only(['index', 'show']);
    });

});
