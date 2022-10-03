<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
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

    public function transfers(){
        $this->hasMany('App\VehicleTranfer');
    }
}
