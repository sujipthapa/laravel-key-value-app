<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'error'   => [
            'code'    => "OK",
            'message' => 'Healthy',
            'details' => "App is up & running.",
        ],
    ], 200);
});

// Web access not authroized

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'error'   => [
            'code'    => "UNAUTHORIZED",
            'message' => 'Unauthorized',
            'details' => "Unauthorized web access.",
        ],
    ], 401);
});
