<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleStation extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'type',
        'status'
    ];

    protected $casts = [
        'name' => 'string',
        'phone' => 'string',
        'type' => 'integer',
        'status' => 'integer'
    ];
    //
    public function transfers(){
        return $this->hasMany('App\Models\VehicleTranfer');
    }

    public function vehicle_station_details(){
        return $this->hasMany('App\Models\VehicleStationDetail');
    }

    public function medias(){
        return $this->morphMany('App\Models\Media', 'mediaable');
    }
}
