<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\VehicleStation;
use Illuminate\Http\Request;

class StationRepository
{
    public function create(array $inputs){
        $station = VehicleStation::create($inputs);
        $station->save();

        return $station;
    }

    public function  update($id, array $inputs){
        $station = VehicleStation::findOrFail($id);
        $station->update($inputs);
        return $station;
    }

    public function delete($id){
        $station = VehicleStation::findOrFail($id);
        return $station->delete();
    }

    public function search($kw){
        $station = VehicleStation::where('name', 'like', '%'.$kw.'%')->paginate(2);
        return $station;
    }
}
