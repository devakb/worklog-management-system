<?php

use App\Http\Middleware\OnlyActiveUsers;
use App\Http\Middleware\OnlyAdminRoutes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Route::redirect('/', 'login', 301);

Auth::routes();

Route::middleware(['auth', OnlyActiveUsers::class])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::view('about', 'about')->name('about');

    Route::middleware(OnlyAdminRoutes::class)->group(function () {

        Route::get('users/{user}/status-toggle', [\App\Http\Controllers\UserController::class, 'statusToggle'])->name('users.status-toggle');
        Route::resource('users', \App\Http\Controllers\UserController::class);

        Route::get('projects/{project}/members', [\App\Http\Controllers\ProjectController::class, 'members'])->name('projects.members.index');
        Route::get('projects/{project}/members/{projectAsignee}/status-toggle', [\App\Http\Controllers\ProjectController::class, 'membersStatusToggle'])->name('projects.members.status-toggle');
        Route::post('projects/{project}/members/store', [\App\Http\Controllers\ProjectController::class, 'members_store'])->name('projects.members.store');

        Route::resource('projects', \App\Http\Controllers\ProjectController::class);

    });

    Route::get('work-logs/visualize/{user}', [ \App\Http\Controllers\WorkLogController::class, 'visualize'])->name('work-logs.visualize');
    Route::resource('work-logs', \App\Http\Controllers\WorkLogController::class)->except(['show']);

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
