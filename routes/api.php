<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/token', [AuthController::class, 'generateToken']);

    // Users
    Route::apiResource('users', UserController::class)->only(['index']);
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('users', [UserController::class, 'store'])->name('users.store')
        ->middleware('token-is-valid');

    // Positions
    Route::apiResource('positions', PositionsController::class)->only('index');
});

