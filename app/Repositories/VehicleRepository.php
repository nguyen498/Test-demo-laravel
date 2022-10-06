<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VehicleRepository
{
    public function create(array $inputs)
    {
        $vehicle = Vehicle::create($inputs);
        return $vehicle;
    }

    public function update($id, array $inputs)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($inputs);
        return $vehicle;
    }

    public function delete($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return $vehicle->delete();
    }

    public function search($kw)
    {
        $vehicle = Vehicle::where('name', 'like', '%'.$kw.'%')->paginate(10);
        return $vehicle;
    }
}
