<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum' , 'throttle:check-user'])->name('check-user');

require_once __DIR__."/auth.php";
