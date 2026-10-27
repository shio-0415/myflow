<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::post('tasks/{task}/favorite', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('tasks.favorite');
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::post('tasks/{task}/subtasks', [\App\Http\Controllers\SubtaskController::class, 'store'])->name('subtasks.store');
    Route::put('subtasks/{subtask}', [\App\Http\Controllers\SubtaskController::class, 'update'])->name('subtasks.update');
    Route::delete('subtasks/{subtask}', [\App\Http\Controllers\SubtaskController::class, 'destroy'])->name('subtasks.destroy');
});
