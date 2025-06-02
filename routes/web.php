<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard routes with role middleware
    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])
        ->middleware('role:super-admin')
        ->name('dashboard.super-admin');
        
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('dashboard.admin');
        
    Route::get('/dashboard/inventory', [DashboardController::class, 'inventory'])
        ->middleware('role:admin-inventory')
        ->name('dashboard.inventory');
        
    Route::get('/dashboard/finance', [DashboardController::class, 'finance'])
        ->middleware('role:admin-finance')
        ->name('dashboard.finance');
});

// Default redirect
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            return redirect('/dashboard/super-admin');
        } elseif ($user->hasRole('admin')) {
            return redirect('/dashboard/admin');
        } elseif ($user->hasRole('admin-inventory')) {
            return redirect('/dashboard/inventory');
        } elseif ($user->hasRole('admin-finance')) {
            return redirect('/dashboard/finance');
        }
    }
    return redirect('/login');
});