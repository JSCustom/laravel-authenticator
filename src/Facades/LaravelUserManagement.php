<?php

namespace JSCustom\LaravelAuthenticator\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelAuthenticator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-authenticator';
    }
}
?>