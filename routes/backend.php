<?php

use App\Http\Controllers\Auth\Backend\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::prefix('admin')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('logout', [LoginController::class, 'destroy'])->name('admin.logout');

//    Route::middleware(['auth', 'role:admin'])->group(function () {
//        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
//        Route::resource('users', UserManagementController::class);
//    });
});
