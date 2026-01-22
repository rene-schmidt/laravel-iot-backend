<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RsCoding\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/projects', [HomeController::class, 'projects'])->name('projects');

Route::get('/projects/laravel', [HomeController::class, 'laravel'])->name('projects.laravel');

Route::get('/projects/laravel/demo', [HomeController::class, 'laravelDemo'])->name('projects.laravel.demo');