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
        $accessToken = $user->data->createToken('abilities', config('authenticator.abilities'));
        return response(['status' => $user->status, 'message' => $user->message, 'payload' => ['user' => $user->data, 'access_token' => $accessToken->plainTextToken]], HttpServiceProvider::OK);
    }
    public function logout(Request $request)
    {
        if (config('authenticator.sanctum.enabled')) {
            if (!Auth::user()->tokenCan('auth-logout')) {
                return response(['status' => false, 'message' => HttpServiceProvider::FORBIDDEN_ACCESS_MESSAGE], HttpServiceProvider::FORBIDDEN_ACCESS);
            }
        }
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return response(['message' => 'Logout successful.'], HttpServiceProvider::OK);
    }
    public function register(Request $request)
    {
        $user = $this->_user->register($request);
        if (!$user->status) {
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::OK);
    }
    public function forgotPassword(Request $request)
    {
        $user = $this->_user->forgotPassword($request);
        if (!$user->status) {
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        return response(['status' => $user->status, 'message' => $user->message, 'payload' => ['reset_password_token' => $user->data]], HttpServiceProvider::OK);
    }
    public function resetPassword(Request $request, $resetPasswordToken)
    {
        if (!$resetPasswordToken) {
            return response(['status' => false, 'message' => 'Please provide a valid password reset token.'], HttpServiceProvider::BAD_REQUEST);
        }
        $user = $this->_user->resetPassword($request, $resetPasswordToken);
        if (!$user->status) {
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::OK);
    }
    public function changePassword(Request $request)
    {
        if (config('authenticator.sanctum.enabled')) {
            if (!Auth::user()->tokenCan('auth-change-password')) {
                return response(['status' => false, 'message' => HttpServiceProvider::FORBIDDEN_ACCESS_MESSAGE], HttpServiceProvider::FORBIDDEN_ACCESS);
            }
        }
        $user = $this->_user->changePassword($request);
        if (!$user->status) {
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::OK);
    }
}