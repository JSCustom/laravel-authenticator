<?php

namespace JSCustom\LaravelSanctumAuth\Http\Controllers\Authenticator;

use JSCustom\LaravelSanctumAuth\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JSCustom\LaravelSanctumAuth\Providers\HttpServiceProvider;

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
        return response(['status' => true, 'message' => 'Logout successful.'], HttpServiceProvider::OK);
    }
    public function register(Request $request)
    {
        $user = $this->_user->saveData($request);
        if (!$user->status) {
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        $user->data->userRole;
        $request->request->add(['user_id' => $user->data->id]);
        $userProfile = $this->_userProfile->saveData($request);
        if (!$userProfile->status) {
            $this->_user->find($request->user_id)->delete();
            return response(['status' => $userProfile->status, 'message' => $userProfile->message], HttpServiceProvider::BAD_REQUEST);
        }
        $user->data->userProfile;
        return response(['status' => $user->status, 'message' => $user->message, 'payload' => ['user' => $user->data]], HttpServiceProvider::CREATED);
    }
    public function forgotPassword(Request $request)
    {
        $user = $this->_user->forgotPassword($request);
        if (!$user->status) {
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        return response(['status' => $user->status, 'message' => $user->message, 'payload' => ['reset_password_token' => $user->data]], HttpServiceProvider::OK);
    }
    public function resetPassword(Request $request)
    {
        if (!$request->reset_password_token) {
            return response(['status' => false, 'message' => 'Please provide a valid reset password token.'], HttpServiceProvider::BAD_REQUEST);
        }
        $user = $this->_user->resetPassword($request);
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