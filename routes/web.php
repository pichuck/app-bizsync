<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Halaman pertama yang muncul (root)
Route::redirect('/', '/login-finance');

// Halaman login baru
Route::get('/login-finance', function () {
    return Inertia::render('Login');
})->name('login-finance');

// Halaman dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

// ... rute lainnya