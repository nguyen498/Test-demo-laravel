<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleTranfer extends Model
{
    //
    public function employee (){
        return $this->belongsTo('App\Employee', 'create_by', 'update_by');
    }

    public function vehicleDetailTransfer(){
        return $this->hasOne('App\VehicleTranferDetail');
    }

    public function station(){
        return $this->belongsTo('App\VehicleStaion', 'from_vehicle_station_id', 'to_vehicle_station_id');
    }

}
