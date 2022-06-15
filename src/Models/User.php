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
  public function saveData($request, $id = NULL)
  {
    if (!config('authenticator.model.authenticator.status.required')) {
      $request->status = config('authenticator.model.authenticator.status.default');
    }
    if (!config('authenticator.model.authenticator.role_id.required')) {
      $request->role_id = config('authenticator.model.authenticator.role_id.default');
    }
    $validator = Validator::make($request->all(), [
      'username' => [
        config('authenticator.model.authenticator.username.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.username.type'),
        config('authenticator.model.authenticator.username.unique') ? 'unique:users,username,'.$id.',id' : '',
        'min:' . config('authenticator.model.authenticator.username.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.username.maxlength') ?? 255
      ],
      'email' => [
        config('authenticator.model.authenticator.email.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.email.type'),
        config('authenticator.model.authenticator.email.unique') ? 'unique:users,email,'.$id.',id' : '',
        'min:' . config('authenticator.model.authenticator.email.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.email.maxlength') ?? 255
      ],
      'password' => [
        config('authenticator.model.authenticator.password.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.password.type'),
        'min:' . config('authenticator.model.authenticator.password.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.password.maxlength') ?? 255
      ],
      'status' => [
        config('authenticator.model.authenticator.status.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.status.type')
      ],
      'role_id' => [
        config('authenticator.model.authenticator.role_id.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.role_id.type')
      ]
    ]);
    if ($validator->stopOnFirstFailure()->fails()) {
      $errors = $validator->errors();
      return (object)['status' => false, 'message' => $errors->first()];
    }
    $password = strtoupper(Str::random(8));
    if ($request->password) {
      $password = $request->password;
    }
    if ($id) {
      $user = User::updateOrCreate([
        'id' => $id
      ],
      [
        'username' => $request->username ?? NULL,
        'email' => $request->email ?? NULL,
        'status' => $request->status,
        'role_id' => $request->role_id
      ]);
    } else {
      $user = User::updateOrCreate([
        'id' => $id
      ],
      [
        'username' => $request->username ?? NULL,
        'email' => $request->email ?? NULL,
        'password' => Hash::make($password),
        'status' => $request->status,
        'role_id' => $request->role_id
      ]);
    }
    if (!$user) {
      return (object)['status' => false, 'message' => 'Failed to register.'];
    }
    $user->password_unhashed = $password;
    return (object)['status' => true, 'message' => 'Register success.', 'data' => $user];
  }
  public function forgotPassword($request)
  {
    $validate = User::whereEmail($request->email)->first();
    PasswordReset::whereUserId($validate->id)->delete();
    $message = 'Email does not exist. Please try again.';
    if ($validate) {
      $userId = $validate->id;
      $token = Hash::make($validate->id);
      $passwordReset = PasswordReset::store($userId, $request->email, $token);
      $message = 'A forgot password request was sent to your email.';
      if (!$passwordReset) {
        $message = 'Could not generate password reset token. Please try again.';
      }
      return (object)['status' => true, 'message' => $message, 'data' => $token];
    } else {
      return (object)['status' => false, 'message' => $message];
    }
  }
  public function resetPassword($request)
  {
    $passwordReset = PasswordReset::whereToken($request->reset_password_token)->first();
    if (!$passwordReset) {
      return (object)['status' => false, 'message' => 'Reset password token has expired. Please try again.'];
    }
    $validator = Validator::make($request->all(), [
      'new_password' => [
        config('authenticator.model.authenticator.password.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.password.type'),
        'min:' . config('authenticator.model.authenticator.password.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.password.maxlength') ?? 255,
        'confirmed',
      ],
      'new_password_confirmation' => [
        config('authenticator.model.authenticator.password.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.password.type'),
        'min:' . config('authenticator.model.authenticator.password.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.password.maxlength') ?? 255
      ],
    ]);
    if ($validator->stopOnFirstFailure()->fails()) {
      $errors = $validator->errors();
      return (object)['status' => false, 'message' => $errors->first()];
    }
    $user = User::find($passwordReset->user_id)->update([
      'password' =>Hash::make($request->new_password)
    ]);
    if (!$user) {
      return (object)['status' => false, 'message' => 'Could not reset password. Please try again.'];
    }
    PasswordReset::whereToken($request->reset_password_token)->delete();
    return (object)['status' => true, 'message' => 'Password reset successfully.'];
  }
  public function changePassword($request)
  {
    $user = auth()->user();
    $validator = Validator::make($request->all(), [
      'current_password' => [
        config('authenticator.model.authenticator.password.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.password.type'),
        'min:' . config('authenticator.model.authenticator.password.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.password.maxlength') ?? 255,
        function ($attribute, $value, $fail) use ($user) {
            if (!Hash::check($value, $user->password)) {
                $fail('Your password was not updated, since the provided current password does not match.');
            }
        }
      ],
      'new_password' => [
        config('authenticator.model.authenticator.password.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.password.type'),
        'min:' . config('authenticator.model.authenticator.password.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.password.maxlength') ?? 255,
        'confirmed',
        'different:current_password'
      ],
      'new_password_confirmation' => [
        config('authenticator.model.authenticator.password.required') ? 'required' : 'nullable',
        config('authenticator.model.authenticator.password.type'),
        'min:' . config('authenticator.model.authenticator.password.minlength') ?? 0,
        'max:' . config('authenticator.model.authenticator.password.maxlength') ?? 255
      ],
    ]);
    if ($validator->stopOnFirstFailure()->fails()) {
        $errors = $validator->errors();
        return (object)['status' => false, 'message' => $errors->first()];
    }
    $user->fill([
        'password' => Hash::make($request->new_password)
    ])->save();
    return (object)['status' => true, 'message' => 'Password updated successfully.'];
  }
}