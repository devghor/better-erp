<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken;

class AccessToken extends PersonalAccessToken
{
    protected $table = 'personal_access_tokens';
}
