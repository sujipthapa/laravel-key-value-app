<?php

use App\Http\Controllers\KeyValueController;
use Illuminate\Support\Facades\Route;

Route::name('api.')
// ->middleware(['auth:sanctum']) // no auth at this stage.
    ->middleware('throttle:public-api')
    ->group(function () {
        Route::get('object/get_all_records', [KeyValueController::class, 'index'])
            ->name('object.index');

        Route::apiResource('object', KeyValueController::class)
            ->only(['show', 'store']);
    });
