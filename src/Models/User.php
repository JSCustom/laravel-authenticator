<?php

namespace JSCustom\LaravelAuthenticator\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  protected $table = 'users';

  protected $fillable = [
    'username',
    'email',
    'password',
    'status',
    'role_id',
    'email_verified_at'
  ];
  
  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'username' => 'string',
    'email' => 'string',
    'password' => 'string',
    'status' => 'integer',
    'role_id' => 'integer',
    'email_verified_at' => 'datetime'
  ];

  protected $guarded = [];

  public function userProfile() {
    return $this->hasOne(UserProfile::class, 'user_id', 'id');
  }
  public function userRole() {
    return $this->belongsTo(UserRole::class, 'role_id', 'id');
  }
  public function login($request)
  {
    $validator = Validator::make($request->all(), [
      'username' => [
        config('authenticator.model.authenticator.username.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.username.type'),
        'min:' . config('authenticator.model.authenticator.username.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.username.maxlength') ?? 255
      ],
      'email' => [
        config('authenticator.model.authenticator.email.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.email.type'),
        'min:' . config('authenticator.model.authenticator.email.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.email.maxlength') ?? 255
      ],
      'password' => [
        config('authenticator.model.authenticator.password.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.password.type'),
        'min:' . config('authenticator.model.authenticator.password.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.password.maxlength') ?? 255
      ]
    ]);
    if ($validator->stopOnFirstFailure()->fails()) {
      $errors = $validator->errors();
      return (object)['status' => false, 'message' => $errors->first()];
    }
    $login = $request->validate([
      'username' => [
        config('authenticator.model.authenticator.username.required') ? 'required' : 'nullable',
      ],
      'email' => [
        config('authenticator.model.authenticator.email.required') ? 'required' : 'nullable',
      ],
      'password' => [
        config('authenticator.model.authenticator.password.type')
      ]
    ]);
    if (!Auth::attempt($login, $request->remember_me)) {
      return (object)['status' => false, 'message' => 'Invalid login credentials.'];
    }
    $user = Auth::user();
    if (!$user) {
      return (object)['status' => false, 'message' => 'Invalid login credentials.'];
    }
    $user = User::find(Auth::user()->id);
    $user->userProfile;
    $user->userRole;
    return (object)['status' => true, 'message' => 'Welcome, ' . $user->userProfile->first_name . '!', 'data' => $user];
  }
  public function forgotPassword($request)
  {
    $validate = User::whereEmail($request->email)->first();
    $message = 'Email does not exist. Please try again.';
    if ($validate) {
      $userId = $validate->id;
      $token = Hash::make($validate->id);
      $passwordReset = PasswordReset::store($userId, $request->email, $token);
      $message = 'A forgot password request was sent to your email.';
      if (!$passwordReset) {
        $message = 'Could not generate password reset token. Please try again.';
      }
      /* try {
        Mail::to($request->email)->send(new ForgotPassword([
            'logo' => request()->root() . Storage::url('images/logo/logo-full.png'),
            'title' => 'Granite Expo - Forgot Password',
            'body' => 'You forgot your password? We\'re sorry to hear that. Click on the button below to reset your password.',
            'btn_caption' => 'Reset Password',
            'link' => env("APP_URL") .'/reset-password?token='. $token
        ]));
      } catch (\Exception $e) {
          Log::debug($e);
      } */
      return (object)['status' => true, 'message' => $message, 'data' => $token];
    } else {
      return (object)['status' => false, 'message' => $message];
    }
  }
}