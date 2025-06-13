<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController; // Tambahan import
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard routes dengan middleware role
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

    // Finance Admin Routes
    Route::middleware('role:admin-finance')->group(function () {
        // Transaction routes
        Route::get('/dashboard/finance/transactions', [TransactionController::class, 'index'])
            ->name('finance.transactions');
        Route::get('/dashboard/finance/transactions/create', [TransactionController::class, 'create'])
            ->name('finance.transactions.create');
        Route::post('/dashboard/finance/transactions', [TransactionController::class, 'store'])
            ->name('finance.transactions.store');
        Route::get('/dashboard/finance/transactions/{id}', [TransactionController::class, 'show'])
            ->name('finance.transactions.show');
        Route::get('/dashboard/finance/transactions/{id}/edit', [TransactionController::class, 'edit'])
            ->name('finance.transactions.edit');
        Route::put('/dashboard/finance/transactions/{id}', [TransactionController::class, 'update'])
            ->name('finance.transactions.update');
        Route::delete('/dashboard/finance/transactions/{id}', [TransactionController::class, 'destroy'])
            ->name('finance.transactions.destroy');
        
        // Customer routes
        Route::get('/dashboard/finance/customers', [CustomerController::class, 'index'])
            ->name('finance.customers');
        Route::get('/dashboard/finance/customers/create', [CustomerController::class, 'create'])
            ->name('finance.customers.create');  // Ubah dari "customers.create" menjadi "finance.customers.create"
        Route::post('/dashboard/finance/customers', [CustomerController::class, 'store'])
            ->name('finance.customers.store');   // Ubah dari "customers.store" menjadi "finance.customers.store"
        Route::get('/dashboard/finance/customers/{id}', [CustomerController::class, 'show'])
            ->name('finance.customers.show');  // Ubah dari 'customers.show' menjadi 'finance.customers.show'
        Route::get('/dashboard/finance/customers/{id}/edit', [CustomerController::class, 'edit'])
            ->name('finance.customers.edit');  // Ubah jadi finance.customers.edit
        Route::put('/dashboard/finance/customers/{id}', [CustomerController::class, 'update'])
            ->name('finance.customers.update'); // Ubah jadi finance.customers.update
        Route::delete('/dashboard/finance/customers/{id}', [CustomerController::class, 'destroy'])
            ->name('customers.destroy');

        // Supplier routes - PERBAIKAN PENAMAAN ROUTE
        Route::get('/dashboard/finance/suppliers', [SupplierController::class, 'index'])
            ->name('finance.suppliers');
        Route::get('/dashboard/finance/suppliers/create', [SupplierController::class, 'create'])
            ->name('finance.suppliers.create');  // Ubah menjadi lebih konsisten
        Route::post('/dashboard/finance/suppliers', [SupplierController::class, 'store'])
            ->name('finance.suppliers.store');  // Ubah menjadi lebih konsisten
        Route::get('/dashboard/finance/suppliers/{id}', [SupplierController::class, 'show'])
            ->name('finance.suppliers.show');
        Route::get('/dashboard/finance/suppliers/{id}/edit', [SupplierController::class, 'edit'])
            ->name('finance.suppliers.edit');
        Route::post('/dashboard/finance/suppliers/{id}', [SupplierController::class, 'update'])
            ->name('finance.suppliers.update');
        Route::delete('/dashboard/finance/suppliers/{id}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');
    });
});

// Default redirect
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user()->load('roles');
        if ($user->roles->contains('name', 'super-admin')) {
            return redirect('/dashboard/super-admin');
        } elseif ($user->roles->contains('name', 'admin')) {
            return redirect('/dashboard/admin');
        } elseif ($user->roles->contains('name', 'admin-inventory')) {
            return redirect('/dashboard/inventory');
        } elseif ($user->roles->contains('name', 'admin-finance')) {
            return redirect('/dashboard/finance');
        }
    }
    return redirect('/login');
});