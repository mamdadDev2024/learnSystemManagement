<?php

use Illuminate\Support\Facades\Route;
use Modules\Interaction\Http\Controllers\InteractionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('interactions', InteractionController::class)->names('interaction');
});
