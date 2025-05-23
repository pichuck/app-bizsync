<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Login');
})->name('login');

// Route untuk halaman lain yang kamu butuhkan