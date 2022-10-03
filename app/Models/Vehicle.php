<?php

namespace App\Models;

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
        return $this->hasMany('App\Models\VehicleTranferDetail');
    }

    public function medias()
    {
        return $this->morphMany('App\Models\Media', 'mediaable');
    }
}
