<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AuthController;

Route::post('login', [AuthController::class , 'login'])->name('login');
