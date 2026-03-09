<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/meetings', function () {
    return view('meetings');
})->name('meetings');

Route::get('/consulting', function () {
    return view('consulting');
})->name('consulting');

Route::get('/orders', function () {
    return view('orders');
})->name('orders');

Route::get('/joint-projects', function () {
    return view('joint-projects');
})->name('joint-projects');
