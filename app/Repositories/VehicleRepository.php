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

    public function search(array $inputs)
    {
        $query = Vehicle::offset(($inputs['page'] - 1) * $inputs['limit'])->limit($inputs['limit']);
        if (!empty($inputs['kw'])) {
            $query->whereRaw('reference like \'%' . $inputs['kw'] . '%\'');
            $query->whereRaw('name like \'%' . $inputs['kw'] . '%\'');
        }
        $vehicle = $query->orderBy($inputs['order_by'], $inputs['sort'])->get();
        return $vehicle;
    }

    public function check($field, array $input){
        $query = Vehicle::where($field, $input[$field]);
        if(empty($inputs['id'])){
            $query->where('id', '<>', $input['id']);
        }
        $check = $query->first();
        return $check;
    }
}
