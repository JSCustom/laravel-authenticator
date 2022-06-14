<?php
use Illuminate\Support\Facades\Route;
use JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController;

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [AuthenticatorController::class, 'login']);
    Route::post('/register', [AuthenticatorController::class, 'register']);
    Route::post('/forgot-password', [AuthenticatorController::class, 'forgotPassword']);
    Route::post('/reset-password/{reset_password_token}', [AuthenticatorController::class, 'resetPassword']);
});
if (config('authenticator.sanctum.enabled')) {
    Route::group(['middleware' => [
        'auth:sanctum',
        'ability:'.implode(',', config('authenticator.abilities'))
        ]
    ], function() {
        Route::group(['prefix' => 'auth'], function() {
            Route::post('/logout', [AuthenticatorController::class, 'logout']);
            Route::post('/change-password', [AuthenticatorController::class, 'changePassword']);
        });
    });
}
?>