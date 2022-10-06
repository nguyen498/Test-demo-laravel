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

    protected $casts = [
        'user_id' => 'integer',
        'name' => 'string',
        'reference' => 'string',
        'vehicle_number' => 'string',
        'description' => 'string',
        'use_period' => 'string',
        'price' => 'float',
        'status' => 'integer',
    ];

    //
    public function vehicle_tranfer_details()
    {
        return $this->hasMany('App\Models\VehicleTranferDetail');
    }

    public function medias()
    {
        return $this->morphMany('App\Models\Media', 'mediaable')->where('type', '=', 1);
    }

    public function detail_medias()
    {
        return $this->morphMany('App\Models\Media', 'mediaable')->where('type', 2);
    }
}

