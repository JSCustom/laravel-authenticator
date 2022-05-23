<?php
use Illuminate\Support\Facades\Route;
use JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController;
use JSCustom\LaravelAuthenticator\Providers\AuthServiceProvider;

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [AuthenticatorController::class, 'login']);
});

if (config('user.sanctum.enabled')) {
    Route::group(['middleware' => [
        'auth:sanctum',
        'ability:'.implode(',', AuthServiceProvider::AUTHENTICATOR_ABILITIES)
        ]
    ], function() {
        Route::group(['prefix' => 'auth'], function() {
            Route::post('/logout', [AuthenticatorController::class, 'logout']);
        });
    });
} else {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('/logout', [AuthenticatorController::class, 'logout']);
    });
}
?>