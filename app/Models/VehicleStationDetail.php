<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleStationDetail extends Model
{
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
