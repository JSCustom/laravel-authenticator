<?php
use Illuminate\Support\Facades\Route;
use JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController;

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [AuthenticatorController::class, 'login']);
});

if (config('authenticator.sanctum.enabled')) {
    Route::group(['middleware' => [
        'auth:sanctum',
        'ability:'.implode(',', config('authenticator.abilities'))
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