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
    return view('dashboard', ['user' => auth()->user()]);
})->middleware('auth')->name('dashboard');

Route::post('/apiRegister', [UserController::class, 'apiRegister']);
Route::post('/apiLogout', [UserController::class, 'apiLogout']);
Route::post('/apiLogin', [UserController::class, 'apiLogin']);
Route::post('/apiUpdate', [UserController::class, 'apiUpdate'])->middleware('auth')->name('dashboard.update');
Route::post('/apiDeleteUser', [UserController::class, 'apiDeleteUser'])->name('user.delete');