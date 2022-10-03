<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTranferDetail extends Model
{
    //
    public function vehicleTranfer(){
        return $this->belongsTo('App\Models\VehicleTranfer', 'vehicle_tranfer_id');
    }

    public function vehicle(){
        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id');
    }

    public function vehicleStationDetail(){
        return $this->belongsTo('App\Models\VehicleStationDetail', 'vehicle_station_id');
    }
}
