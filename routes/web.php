<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

// ── Guest routes ──────────────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Root redirect ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ── Protected routes (require login) ─────────────────────────────────────────
Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/dashboard',      fn() => view('dashboard'))->name('dashboard');
    Route::get('/consulting',     fn() => view('consulting'))->name('consulting');

    // SPA shell — these sections now live inside consulting.blade.php
    // Direct URL access redirects to the SPA with a hash so JS can show the right section
    Route::get('/meetings',       fn() => redirect('/consulting#meetings'))->name('meetings');
    Route::get('/orders',         fn() => redirect('/consulting#orders'))->name('orders');
    Route::get('/joint-projects', fn() => redirect('/consulting#projects'))->name('joint-projects');
});
