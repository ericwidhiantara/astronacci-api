<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('reset-password/{token}/{email}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
