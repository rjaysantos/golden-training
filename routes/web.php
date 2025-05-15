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

Route::post('/apiRegister', [UserController::class, 'apiRegister']);
Route::post('/apiLogin', [UserController::class, 'apiLogin']);
Route::post('/apiLogout', [UserController::class, 'apiLogout']);
Route::patch('/apiUpdate', [UserController::class, 'apiUpdate']);
Route::delete('/apiDeleteUser', [UserController::class, 'apiDeleteUser'])->name('user.delete');