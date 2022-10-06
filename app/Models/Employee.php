<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Employee extends Authenticatable
{
    //
    use Notifiable, hasApiTokens;

    protected $fillable = [
        'name',
        'login_name',
        'password',
        'email',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'name' => 'string',
        'login_name' => 'string',
        'password' => 'string',
        'email' => 'string',
    ];

    public function transfers()
    {
        $this->hasMany('App\Models\VehicleTranfer');
    }
}
