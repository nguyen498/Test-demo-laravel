<?php

namespace App\Repositories;

use App\Models\VehicleStationDetail;

class StationDetailRepository
{
    public function create(array $inputs){
        $stationDetail = VehicleStationDetail::create($inputs);
        return $stationDetail;
    }

    public function update($id, array $inputs){
        $stationDetail = VehicleStationDetail::findOrFail($id);
        $stationDetail->update($inputs);
        return $stationDetail;
    }

    public function delete($id){
        $stationDetail = VehicleStationDetail::findOrFail($id);
        return $stationDetail->delete();
    }

    public function search($kw){
        $stationDetail = VehicleStationDetail::where('name', 'like', '%'.$kw.'%')->get();
        return $stationDetail;
    }
}
