<?php

namespace App\Repositories;

use App\Models\VehicleTranferDetail;

class VehicleTransferDetailRepository
{
    public function create(array $inputs)
    {
        $transferDetail = VehicleTranferDetail::create($inputs);
        return $transferDetail;
    }

    public function update($id, array $inputs)
    {
        $transferDetail = VehicleTranferDetail::find($id);
        return $transferDetail->update($inputs);
    }

    public function delete($id)
    {
        $transferDetail = VehicleTranferDetail::find($id);
        return $transferDetail->delete();
    }

    public function search($kw)
    {
        $result = VehicleTranferDetail::where('reference', 'like', '%'.$kw.'%');
        return $result;
    }
}
