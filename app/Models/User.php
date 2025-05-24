<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $fillable = [
        'name',
        'username',
        'password',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
