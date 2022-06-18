<?php

namespace JSCustom\LaravelSanctumAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class UserRole extends Model
{
  use HasFactory;

  protected $table = 'user_roles';

  protected $fillable = [
    'role',
    'description'
  ];

  protected $casts = [
    'role' => 'string',
    'description' => 'string'
  ];

  // Disable Laravel's mass assignment protection
  protected $guarded = [];
}