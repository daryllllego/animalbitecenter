<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnimalBiteController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Redirect
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

    // Universal Storage Fallback
    Route::get('/storage/{path}', [FileController::class, 'serve'])->where('path', '.*');

    // Animal Bite Center Module
    Route::prefix('animal-bite')->name('animal-bite.')->group(function () {
        Route::get('/dashboard', [AnimalBiteController::class, 'dashboard'])->name('dashboard');
        Route::get('/cash-on-hand', [AnimalBiteController::class, 'cashOnHand'])->name('cash-on-hand');
        Route::get('/patients', [AnimalBiteController::class, 'patients'])->name('patients');
        Route::post('/patients', [AnimalBiteController::class, 'storePatient'])->name('patients.store');
        Route::get('/masterlist', [AnimalBiteController::class, 'masterlist'])->name('masterlist');
        Route::post('/masterlist', [AnimalBiteController::class, 'storeEntry'])->name('masterlist.store');
        Route::post('/deductions', [AnimalBiteController::class, 'storeDeduction'])->name('deductions.store');
        Route::post('/set-date', [AnimalBiteController::class, 'setDate'])->name('set-date');
        Route::post('/update-daily-stats', [AnimalBiteController::class, 'updateDailyStats'])->name('update-daily-stats');
        Route::post('/cash-on-hand', [AnimalBiteController::class, 'storeCashRecord'])->name('cash-on-hand.store');
        Route::post('/inventory', [AnimalBiteController::class, 'storeInventory'])->name('inventory.store');
        Route::get('/inventory', [AnimalBiteController::class, 'inventory'])->name('inventory');
    });

});
