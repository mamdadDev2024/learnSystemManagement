<?php

use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\LessonController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('lesson', LessonController::class)->except('update')->names('lesson');
    Route::put('lesson/{Course}' , [LessonController::class , 'update'])->name('update');
});
