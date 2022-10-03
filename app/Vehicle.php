<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    //
    public function vehicleTranferDetails(){
        return $this->hasMany('App\VehicleTranferDetail');
    }

    public function medias(){
        return $this->morphMany('App\Media', 'mediaable');
    }
}
