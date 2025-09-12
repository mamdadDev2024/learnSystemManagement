<?php

use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\LessonController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('lessons', LessonController::class)->names('lesson');
});
