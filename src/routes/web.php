<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
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

    Route::resource('events', EventController::class);
    
    // イベント参加申込ルート
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.register');
    Route::delete('/events/{event}/unregister', [EventRegistrationController::class, 'destroy'])->name('events.unregister');
    Route::get('/events/{event}/participants', [EventRegistrationController::class, 'participants'])->name('events.participants');
});

require __DIR__.'/auth.php';
