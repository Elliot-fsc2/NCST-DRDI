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
        Route::livewire('/home', 'teacher::home-new')->name('teacher.home');
        Route::livewire('/my-sections', 'teacher::my-sections')->name('teacher.my-sections');
        Route::livewire('/my-sections/{section}', 'teacher::my-sections.view')->name('teacher.my-sections.view');
        Route::livewire('/my-sections/{section}/groups', 'pages::teacher.my-sections.view.groups')->name('teacher.my-sections.view.groups');
        Route::livewire('/my-sections/{section}/students', 'pages::teacher.my-sections.view.students')->name('teacher.my-sections.view.students');
    });

// Student Routes
Route::middleware(['auth'])
    ->prefix('student')
    ->group(function () {
        Route::get('/home', function () {})->name('student.home');
    });
