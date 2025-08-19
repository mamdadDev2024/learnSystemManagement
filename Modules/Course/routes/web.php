<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('courses', CourseController::class)->names('course');
});
