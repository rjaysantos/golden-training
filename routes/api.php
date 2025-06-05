<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/apiRegister', [UserController::class, 'apiRegister']);
Route::post('/apiLogin', [UserController::class, 'apiLogin']);
Route::post('/apiLogout', [UserController::class, 'apiLogout']);
Route::patch('/apiUpdate', [UserController::class, 'apiUpdate']);
Route::delete('/apiDeleteUser', [UserController::class, 'apiDeleteUser'])->name('user.delete');