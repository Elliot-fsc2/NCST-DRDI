<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::redirect('/', '/login');

// Teacher Routes
Route::middleware(['auth', 'teacher'])
    ->prefix('teacher')
    ->group(function () {
        Route::livewire('/home', 'pages::teacher.home')->name('teacher.home');
    });

// Student Routes
Route::middleware(['auth'])
    ->prefix('student')
    ->group(function () {
        Route::get('/home', function () {
        })->name('student.home');
    });
