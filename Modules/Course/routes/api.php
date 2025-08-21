<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('course', CourseController::class)->names('course');
});
