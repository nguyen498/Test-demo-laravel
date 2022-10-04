<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\VehicleRepository;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    //

    protected $vehicle;

    public function __construct(VehicleService $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function create(Request $request){
        $data = $this->vehicle->create($request);
        return response()->json([
            'data' => $data,
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
            'message' => 'delete success'
        ],200);
    }

    public function search($kw){
        $result = $this->vehicle->search($kw);
        return
            response()->json([
            'data' => $result
        ], 200);
    }

    public function addCoverMedia($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->vehicle->addCoverMedia($id, $request);
        return response()->json([
           'data' => $result
        ]);
    }

    public function addDetailMedia($id, Request $request){
        $result = $this->vehicle->addDetailMedia($id, $request);
        return response()->json([
            'data' => $result
        ]);
    }
}
