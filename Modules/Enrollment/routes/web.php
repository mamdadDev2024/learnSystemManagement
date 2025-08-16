<?php

use Illuminate\Support\Facades\Route;
use Modules\Enrollment\Http\Controllers\EnrollmentController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('enrollments', EnrollmentController::class)->names('enrollment');
});
