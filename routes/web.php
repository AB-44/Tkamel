<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// ── Guest routes ──────────────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Root redirect ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ── Protected routes — any authenticated user ─────────────────────────────────
Route::middleware(AuthMiddleware::class)->group(function () {

    // ── Admin routes ──────────────────────────────────────────────────────────
    Route::middleware(RoleMiddleware::class . ':admin,association')->group(function () {
        Route::get('/dashboard',      fn() => view('dashboard'))->name('dashboard');
        Route::get('/consulting',     fn() => view('consulting'))->name('consulting');

        Route::get('/volunteer',      fn() => redirect('/consulting#volunteer'))->name('volunteer');
        Route::get('/meetings',       fn() => redirect('/consulting#meetings'))->name('meetings');
        Route::get('/orders',         fn() => redirect('/consulting#orders'))->name('orders');
        Route::get('/joint-projects', fn() => redirect('/consulting#projects'))->name('joint-projects');
    });

    // ── Regular user routes ───────────────────────────────────────────────────
    Route::middleware(RoleMiddleware::class . ':user')->group(function () {
        Route::get('/user/dashboard',      fn() => view('user.dashboard'))->name('user.dashboard');
        Route::get('/user/consulting',     fn() => view('user.consulting'))->name('user.consulting');
        Route::get('/user/services',       fn() => view('user.services'))->name('user.services');

        Route::get('/user/meetings',       fn() => redirect('/user/consulting#meetings'))->name('user.meetings');

        Route::get('/user/joint-projects', fn() => redirect('/user/consulting#projects'))->name('user.joint-projects');
    });

});

// ── JSON API endpoints — must be in web.php to share the session middleware ──
// (Laravel's api middleware group doesn't start the session, so Auth::check()
//  would always return false for session-based logins if defined in api.php)
Route::prefix('api')
    ->middleware([AuthMiddleware::class, RoleMiddleware::class . ':admin'])
    ->group(function () {

        // Association registration requests
        Route::get('/association-requests',
            [App\Http\Controllers\Admin\AssociationRequestController::class, 'index']);
        Route::post('/association-requests/{id}/approve',
            [App\Http\Controllers\Admin\AssociationRequestController::class, 'approve']);
        Route::post('/association-requests/{id}/reject',
            [App\Http\Controllers\Admin\AssociationRequestController::class, 'reject']);
        Route::post('/association-requests/{id}/review',
            [App\Http\Controllers\Admin\AssociationRequestController::class, 'requestReview']);

        // Notifications
        Route::get('/notifications',
            [App\Http\Controllers\Admin\NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read',
            [App\Http\Controllers\Admin\NotificationController::class, 'markRead']);
        Route::post('/notifications/read-all',
            [App\Http\Controllers\Admin\NotificationController::class, 'markAllRead']);
    });
