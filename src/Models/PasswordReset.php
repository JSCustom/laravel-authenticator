<?php

namespace JSCustom\LaravelAuthenticator\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use JSCustom\LaravelAuthenticator\Models\User;

class PasswordReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'token'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'email' => 'string',
        'token' => 'string'
    ];

    public function store($user_id, $email, $token) {
        $data = PasswordReset::create([
            'user_id' => $user_id,
            'email' => $email,
            'token' => $token
        ]);
        return $data;
    }
    public function reset($request, $user_id) {
        $validated = $request->validate([
            'new_password' => [
                'required',
                'max:70',
                'confirmed',
            ],
            'new_password_confirmation' => [
                'required',
                'max:70'
            ],
        ]);
        $message = "Email not found. Please try again.";
        
        $user = User::find($user_id);
        if ($user) {
            User::find($user_id)
            ->update([
                'password' =>Hash::make($validated['new_password'])
            ]);
            $message = "Password reset successful.";
        }
        return $message;
    }
}
