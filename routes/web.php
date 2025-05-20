<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home'); // registration page
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/apiGetAuthenticatedUser', [UserController::class, 'apiGetAuthenticatedUser']);