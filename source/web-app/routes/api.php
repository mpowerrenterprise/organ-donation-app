<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\AuthController;

// Mobile Apps

Route::post('/login/mobile', [AuthController::class, 'login']);
Route::post('register/mobile', [AuthController::class, 'register']);
