<?php

use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\LessonController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('lesson', LessonController::class)->except(['update' , 'store' , 'index'])->names('lesson');
    Route::put('lesson/{Course}' , [LessonController::class , 'update'])->name('update');
    Route::post('lesson/{Course}' , [LessonController::class , 'store'])->name('store');
    Route::get('lesson/{Course}/index' , [LessonController::class , 'index'])->name('index');
});
