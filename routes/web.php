<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnimalBiteController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Redirect
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/change-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Universal Storage Fallback
    Route::get('/storage/{path}', [FileController::class, 'serve'])->where('path', '.*');

    // Animal Bite Center Module
    Route::prefix('animal-bite')->name('animal-bite.')->group(function () {
        Route::get('/dashboard', [AnimalBiteController::class, 'dashboard'])->name('dashboard');
        Route::get('/monthly-report', [AnimalBiteController::class, 'monthlyReport'])->name('monthly-report');
        Route::get('/export-daily', [AnimalBiteController::class, 'exportDailyReport'])->name('export-daily');
        Route::get('/export-monthly', [AnimalBiteController::class, 'exportMonthlyReport'])->name('export-monthly');
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
