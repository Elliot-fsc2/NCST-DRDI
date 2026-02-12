<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return redirect()->route('login');
});

Route::redirect('/', '/login');

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
        Route::livewire('/my-sections/{section}/students/{student}/edit', 'teacher::my-sections.students.edit')->name('my-sections.students.edit');
        Route::livewire('/my-sections/{section}/groups/{group}/view', 'teacher::groups.view')->name('my-sections.groups.view');
        Route::livewire('/my-sections/{section}/groups/{group}/add-members', 'teacher::groups.add-members')->name('my-sections.groups.add-members');

        Route::livewire('/all-groups', 'teacher::all-groups.view')->name('all-groups.view');
        Route::livewire('/all-groups/assigned/{group}', 'teacher::all-groups.assigned.view')->name('all-groups.assigned.view');

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
        Route::livewire('/masterlist', 'rdo::masterlist')->name('masterlist');
    });
