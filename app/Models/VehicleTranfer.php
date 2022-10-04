<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTranfer extends Model
{
    //
    public function employee (){
        return $this->belongsTo('App\Models\Employee', 'create_by', 'update_by');
    }

    public function vehicle_detail_transfer(){
        return $this->hasOne('App\Models\VehicleTranferDetail');
    }

    public function station(){
        return $this->belongsTo('App\VehicleStaion', 'from_vehicle_station_id', 'to_vehicle_station_id');
    }

}
