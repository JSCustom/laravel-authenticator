<?php

namespace JSCustom\LaravelAuthenticator\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct(
        \JSCustom\LaravelAuthenticator\Models\User $User,
        \JSCustom\LaravelAuthenticator\Models\UserProfile $UserProfile,
        \JSCustom\LaravelAuthenticator\Models\UserRole $UserRole
    ) {
        $this->_user = $User;
        $this->_userProfile = $UserProfile;
        $this->_userRole = $UserRole;
    }
}