<?php

namespace JSCustom\LaravelSanctumAuth\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelSanctumAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-sanctum-auth';
    }
}
?>