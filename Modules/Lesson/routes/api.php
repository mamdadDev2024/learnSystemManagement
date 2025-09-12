<?php

use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\LessonController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('lesson', LessonController::class)->names('lesson');
});
