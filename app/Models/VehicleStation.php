<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleStation extends Model
{
    //
    public function transfers(){
        return $this->hasMany('App\Models\VehicleTranfer');
    }

    public function vehicleStationDetails(){
        return $this->hasMany('App\Models\VehicleStationDetail');
    }

    public function medias(){
        return $this->morphMany('App\Models\Media', 'mediaable');
    }
}
