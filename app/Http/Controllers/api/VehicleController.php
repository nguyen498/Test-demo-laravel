<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
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

    public function create(Request $request)
    {
        $data = $this->vehicle->create($request);
        if ($data['code'] != '200') {
            return response()->json([
                'success' => false,
                'code' => $data['code'],
                'error' => $data['message']
            ]);
        }
        return response()->json([
            'success' => true,
            'code' => $data['code'],
            'data' => $data['data']
        ]);
    }

    public function update($id, Request $request)
    {
        $data = $this->vehicle->update($id, $request);
        if ($data['code'] != '200') {
            return response()->json([
                'success' => false,
                'code' => $data['code'],
                'error' => $data['message']
            ]);
        }
        return response()->json([
            'success' => true,
            'code' => $data['code'],
            'data' => $data['data']
        ]);
    }

    public function delete($id)
    {
        $this->vehicle->delete($id);
        return response()->json([
            'message' => 'delete success'
        ], 200);
    }

    public function search(Request $request)
    {
        $result = $this->vehicle->search($request->all());
        return VehicleResource::collection($result);
//      return response()->json([
//            'data' => $result
//        ], 200);
    }

    public function findId($id)
    {
        $data = Vehicle::findOrFail($id);
//        $vehicle = VehicleResource::collection($data);
        return new VehicleResource($data);
    }
}
