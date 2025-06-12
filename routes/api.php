<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'submitForgetPasswordForm'])->name('forgot.password.mail');
Route::get('/check-app-version', [HomeController::class, 'checkAppVersion']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/profile', [AuthController::class, 'userProfile']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
    Route::post('/profile/change-password', [UserController::class, 'changePassword']);
    Route::post('/profile/change-profile-picture', [UserController::class, 'changeProfilePicture']);
});
