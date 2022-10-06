<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\VehicleStation;
use Illuminate\Http\Request;

class StationRepository
{
    public function create(array $inputs)
    {
        $station = VehicleStation::create($inputs);
        $station->save();

        return $station;
    }

    public function update($id, array $inputs)
    {
        $station = VehicleStation::findOrFail($id);
        $station->update($inputs);
        return $station;
    }

    public function delete($id)
    {
        $station = VehicleStation::findOrFail($id);
        return $station->delete();
    }

    public function search(array $inputs)
    {
        $query = VehicleStation::offset($inputs['page'] - 1 * $inputs['limit'])->limit($inputs['limit']);
        if (!empty($inputs['kw'])) {
            $query->whereRaw('reference like \'%' . $inputs['kw'] . '%\'');
            $query->whereRaw('name like \'%' . $inputs['kw'] . '%\'');
        }
        $station = $query->orderBy($inputs['order_by'], $inputs['sort'])->get();
        return $station;
    }
    public function check($field, array $input){
        $query = VehicleStation::where($field, $input[$field]);
        if(!empty($inputs['id'])){
            $query->where('id', '<>', $input['id']);
        }
        $check = $query->first();
        return $check;
    }
}
