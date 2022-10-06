<?php

namespace App\Repositories;

use App\Models\VehicleStationDetail;

class StationDetailRepository
{
    public function create(array $inputs)
    {
        $stationDetail = VehicleStationDetail::create($inputs);
        return $stationDetail;
    }

    public function update($id, array $inputs)
    {
        $stationDetail = VehicleStationDetail::findOrFail($id);
        $stationDetail->update($inputs);
        return $stationDetail;
    }

    public function delete($id)
    {
        $stationDetail = VehicleStationDetail::findOrFail($id);
        return $stationDetail->delete();
    }

    public function search(array $inputs)
    {
        $query = VehicleStationDetail::offset(($inputs['page'] - 1) * $inputs['limit'])->limit($inputs['limit']);
        $stationDetail = $query->orderBy($inputs['order_by'], $inputs['sort'])->get();
        return $stationDetail;
    }
}
