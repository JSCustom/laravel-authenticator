<?php

namespace JSCustom\LaravelSanctumAuth\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct(
        \JSCustom\LaravelSanctumAuth\Models\User $User,
        \JSCustom\LaravelSanctumAuth\Models\UserProfile $UserProfile,
        \JSCustom\LaravelSanctumAuth\Models\UserRole $UserRole
    ) {
        $this->_user = $User;
        $this->_userProfile = $UserProfile;
        $this->_userRole = $UserRole;
    }
}