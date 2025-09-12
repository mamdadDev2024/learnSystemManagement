<?php

use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\LessonController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('lesson', LessonController::class)->except(['update' , 'store' , 'index'])->names('lesson');
    Route::put('lesson/{Lesson}' , [LessonController::class , 'update'])->name('update');
    Route::post('lesson/{Course}' , [LessonController::class , 'store'])->name('store');
    Route::get('lesson/{Course}/index' , [LessonController::class , 'index'])->name('index');

    Route::get('progress/{Lesson}' , [LessonController::class , 'GetProgress'])->name('lesson.progress.get');
    Route::put('progress' , [LessonController::class , 'UpdateProgress'])->name('lesson.progress.update');
    Route::post('progress/{Lesson}' , [LessonController::class , 'CreateProgress'])->name('lesson.progress.create');
});
