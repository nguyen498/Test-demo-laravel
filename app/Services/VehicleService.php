<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleService
{
    protected $vehicleRepository;

    public function __construct (VehicleRepository $vehicleRepository){
        $this->vehicleRepository = $vehicleRepository;
    }

    public function create(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'vehicle_number' => 'required',
            'price' => 'required | numeric',
            'cover_media' => 'required | mimes: jpg, png, jpeg',
            'detail_media' => 'required | mimes: jpg, png, jpeg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }

        return $this->vehicleRepository->create($request);
    }

    public function update($id, Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'vehicle_number' => 'required',
            'price' => 'required | numeric'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }

        return $this->vehicleRepository->update($id, $request);
    }

    public function delete($id){
        return $this->vehicleRepository->delete($id);
    }

    public function search($kw){
        return $this->vehicleRepository->search($kw);
    }

    public function addCoverMedia($id, Request $request)
    {
        $medias = Vehicle::find($id)->medias()->where('type', '=', '1')->get();
        if($medias->count() < 1){
            return $this->vehicleRepository->addCoverMedia($id ,$request);
        }
        else {
            return response()->json([
               'error' => 'Media cover is only 1'
            ]);
        }
    }

    public function addDetailMedia($id, Request $request)
    {
        $medias = Vehicle::find($id)->medias()->where('mediaable_id', '=', '2')->get();
        if($medias->count() < 5){
            return $this->vehicleRepository->addDetailMedia($id ,$request);
        }
        else {
            return response()->json([
                'error' => 'Media detail dont more than 5'
            ]);
        }
    }
}
