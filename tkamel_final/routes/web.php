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
    Route::get('/meetings',       fn() => view('meetings'))->name('meetings');
    Route::get('/consulting',     fn() => view('consulting'))->name('consulting');
    Route::get('/orders',         fn() => view('orders'))->name('orders');
    Route::get('/joint-projects', fn() => view('joint-projects'))->name('joint-projects');
});
