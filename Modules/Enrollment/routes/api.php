<?php

use Illuminate\Support\Facades\Route;
use Modules\Enrollment\Http\Controllers\EnrollmentController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
});
