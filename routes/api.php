<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // API untuk search contacts
    Route::get('/contacts/search', [TransactionController::class, 'searchContacts']);
    
    // API untuk mendapatkan semua customers untuk dropdown
    Route::get('/customers', [TransactionController::class, 'getCustomers']);
    
    // API untuk mendapatkan semua suppliers untuk dropdown
    Route::get('/suppliers', [TransactionController::class, 'getSuppliers']);
});