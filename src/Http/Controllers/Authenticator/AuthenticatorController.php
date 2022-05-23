<?php

namespace JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator;

use JSCustom\LaravelAuthenticator\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JSCustom\LaravelAuthenticator\Providers\HttpServiceProvider;

class AuthenticatorController extends Controller
{
    public function login(Request $request)
    {
        $user = $this->_user->login($request);
        if (!$user->status) {
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        $user->data->userProfile;
        $user->data->userRole;
        return response(['status' => $user->status, 'message' => 'hello', 'payload' => ['user' => $user->data]], HttpServiceProvider::OK);
    }
}