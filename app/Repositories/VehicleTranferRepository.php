<?php

namespace App\Repositories;

use App\Models\VehicleTranfer;

class VehicleTranferRepository
{
    public function create(array $inputs)
    {
        $transfer = VehicleTranfer::create($inputs);
        return $transfer;
    }

    public function update($id, array $inputs)
    {
        $tranfer = VehicleTranfer::find($id);
        $tranfer->update($inputs);
        return $tranfer;
    }

    public function delete($id)
    {
        $tranfer = VehicleTranfer::find($id);
        return $tranfer->delete($id);
    }

    public function search($kw)
    {
        $result = VehicleTranfer::where('refrence', 'like', '%'.$kw.'%');
        return $result;
    }
}
