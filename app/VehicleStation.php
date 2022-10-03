<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleStation extends Model
{
    //
    public function transfers(){
        return $this->hasMany('App\VehicleTranfer');
    }

    public function vehicleStationDetails(){
        return $this->hasMany('App\VehicleStationDetail');
    }

    public function medias(){
        return $this->morphMany('App\Media', 'mediaable');
    }
}
