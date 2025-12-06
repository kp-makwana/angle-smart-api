<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $table = 'accounts';
  protected $guarded = [];
  protected $casts = [
    'client_secret' => 'encrypted',
    'api_key' => 'encrypted',
    'totp_secret' => 'encrypted',
    'session_token' => 'encrypted',
    'refresh_token' => 'encrypted',
  ];

}
