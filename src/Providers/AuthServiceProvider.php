<?php

namespace JSCustom\LaravelAuthenticator\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    CONST USER_MANAGEMENT_ABILITIES = [
        'auth-login',
        'auth-logout',
        'auth-signup',
        'auth-forgot-password',
        'auth-reset-password'
    ];
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
