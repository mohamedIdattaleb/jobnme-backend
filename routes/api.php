<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ContactController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'Register']);
    Route::post('/login', [AuthController::class, 'Login']);
    Route::post('/google-login', [AuthController::class, 'googleLogin']);
    Route::post('/logout', [AuthController::class, 'Logout'])->middleware('auth:sanctum');
});

Route::apiResource('users', UserController::class);

Route::apiResource('works', WorkController::class);

Route::apiResource('contact', ContactController::class);