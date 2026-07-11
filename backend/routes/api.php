<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Uam\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    /**
     * Auth Module
     */
    Route::prefix('auth')
        ->name('auth.')
        ->group(function (): void {
            Route::post('/login', [LoginController::class, 'login']);
            Route::middleware('auth:sanctum')
                ->group(function (): void {
                    Route::post('/refresh', [LoginController::class, 'refresh']);
                    Route::get('/auth-user', [LoginController::class, 'authUser']);
                });
        });

    Route::middleware('auth:sanctum')
        ->group(function (): void {
            /**
             * UAM Module
             */
            Route::prefix('uam')
                ->name('uam.')
                ->group(function (): void {
                    Route::apiResource('users', UserController::class);
                });
        });
});
