<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    /**
     * Auth Module
     */
    Route::prefix('auth')->group(function (): void {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/refresh', [LoginController::class, 'refresh'])
            ->middleware(['auth:sanctum', 'abilities:issue-access-token']);

        Route::middleware('auth:sanctum')
            ->group(function (): void {
                Route::get('/auth-user', [LoginController::class, 'authUser']);
            });
    });
});
