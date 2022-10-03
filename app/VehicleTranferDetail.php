<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleTranferDetail extends Model
{
    //
    public function vehicleTranfer(){
        return $this->belongsTo('App\VehicleTranfer', 'vehicle_tranfer_id');
    }

    public function vehicle(){
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }

    public function vehicleStationDetail(){
        return $this->belongsTo('App\VehicleStationDetail', 'vehicle_station_id');
    }
}
