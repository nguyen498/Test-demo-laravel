<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'reference',
        'vehicle_number',
        'description',
        'use_period',
        'price',
        'status',
    ];

    //
    public function vehicleTranferDetails()
    {
        return $this->hasMany('App\VehicleTranferDetail');
    }

    public function medias()
    {
        return $this->morphMany('App\Media', 'mediaable');
    }
}
