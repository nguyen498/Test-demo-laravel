<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    //

    protected $vehicle;

    public function __construct(VehicleRepository $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function create(Request $request){
        $data = $this->vehicle->create($request);
        return response()->json([
            'data' => $data
        ], 201);
    }

    public function update($id, Request $request){
        $data = $this->vehicle->update($id, $request);
        return response()->json([
            'data' => $data
        ], 201);
    }

    public function delete($id){
        $this->vehicle->delete($id);
        return response()->json([
            'infor' => 'delete success'
        ],200);
    }

    public function search($kw){
        $vehicle = $this->vehicle->search($kw);
        return $vehicle;
//            response()->json([
//            'data' => $vehicle
//        ], 200);
    }
}
