<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleStationDetail extends Model
{
    //
    public function vehicleTranferDetails(){
        return $this->hasMany('App\VehicleTranferDetail');
    }

    public function vehicleStation(){
        return $this->belongsTo('App\VehicleStaion','vehicle_station_id');
    }

    public function medias(){
        return $this->morphMany('App\Media', 'mediaable');
    }
}
