<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/reset-password', 'resetPassword');
    Route::post('/reset-password-confirm', 'resetPasswordConfirm');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/events', [EventController::class, 'index']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('locations', LocationController::class);
});

