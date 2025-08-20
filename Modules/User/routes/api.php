<?php

use App\Contracts\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\VerificationController;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return ApiResponse::success($request->user());
})->middleware(['auth:sanctum', 'throttle:check-user'])->name('check-user');

Route::as('auth.')->prefix('auth')->group(function () {
    Route::middleware(['guest:sanctum', 'throttle:auth'])->group(function () {
        Route::post('login',                  [AuthController::class, 'login'])->name('login');
        Route::post('register',               [AuthController::class, 'register'])->name('register');
        Route::post('send-verification-code', [VerificationController::class, 'sendVerificationCode'])->name('send.code');
        Route::post('verify-code',            [VerificationController::class, 'verifyCode'])->name('verify');
    });

    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');

    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware(['auth:sanctum'])
        ->name('logout');
});

Route::as('user.')->prefix('user')->middleware(['auth:sanctum', 'throttle:user-actions'])->group(function () {
    Route::delete('', [UserController::class, 'destroy'])->name('delete');
    Route::put('', [UserController::class, 'update'])->name('update');
});
