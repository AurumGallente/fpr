<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TextsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectsController::class, 'create'])->name('projects.create');
    Route::post('/projects/store', [ProjectsController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectsController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{id}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectsController::class, 'destroy'])->name('projects.destroy');

    Route::get('/projects/{id}/texts/create',[TextsController::class, 'create'])->name('projects.texts.create');
    Route::post('/projects/{project_id}/texts/store',[TextsController::class, 'store'])->name('projects.texts.store');
});

require __DIR__.'/auth.php';
