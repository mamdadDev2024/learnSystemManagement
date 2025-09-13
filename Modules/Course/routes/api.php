<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseController;

Route::middleware(['auth:sanctum'])->as('course.')->group(function () {
    Route::apiResource('course', CourseController::class)->middlewareFor(['update' , 'destroy' , 'store'], 'role:teacher|admin');
});
