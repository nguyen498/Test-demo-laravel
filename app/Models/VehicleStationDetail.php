<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleStationDetail extends Model
{
    protected $fillable = [
        'vehicle_station_id',
        'vehicle_id',
        'code',
        'floor',
        'slot',
        'area',
        'gate',
        'period',
        'status',
        'type',
        'reference'
    ];

    protected $casts = [
        'vehicle_station_id' => 'integer',
        'vehicle_id' => 'integer',
        'code' => 'string',
        'floor' => 'string',
        'slot' => 'string',
        'area' => 'string',
        'gate' => 'string',
        'period' => 'float',
        'status' => 'integer',
        'type' => 'integer',
        'reference' => 'string'
    ];
    //
    public function vehicle_tranfer_details(){
        return $this->hasMany('App\Models\VehicleTranferDetail');
    }

    public function vehicle_station(){
        return $this->belongsTo('App\VehicleStaion','vehicle_station_id');
    }

    public function medias(){
        return $this->morphMany('App\Models\Media', 'mediaable');
    }
}
