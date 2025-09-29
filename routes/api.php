<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/reset-password', 'resetPassword');
    Route::post('/reset-password-confirm', 'resetPasswordConfirm');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
