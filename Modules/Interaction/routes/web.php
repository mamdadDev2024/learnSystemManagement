<?php

use Illuminate\Support\Facades\Route;
use Modules\Interaction\Http\Controllers\InteractionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('interactions', InteractionController::class)->names('interaction');
});
