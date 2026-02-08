<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return redirect()->route('login');
});

Route::redirect('/', '/login');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])
    ->prefix('teacher')
    ->as('teacher.')
    ->group(function () {
        Route::livewire('/home', 'teacher::home-new')->name('home');
        Route::livewire('/my-sections', 'teacher::my-sections')->name('my-sections');
        Route::livewire('/my-sections/{section}', 'teacher::my-sections.view')->name('my-sections.view');
        Route::livewire('/my-sections/{section}/students', 'teacher::my-sections.students.create')->name('my-sections.students');
    });

// Student Routes
Route::middleware(['auth', 'student'])
    ->prefix('student')
    ->as('student.')
    ->group(function () {
        Route::livewire('/home', 'student::home')->name('home');
    });

Route::middleware(['auth', 'rdo'])
    ->prefix('rdo')
    ->as('rdo.')
    ->group(function () {
        Route::livewire('/home', 'rdo::home')->name('home');
    });
