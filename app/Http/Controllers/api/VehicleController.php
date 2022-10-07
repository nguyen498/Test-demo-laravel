<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\VehicleService;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    //

    protected $vehicle;
    protected $responseUtil;

    public function __construct(VehicleService $vehicle,
                                ResponseUtil $responseUtil)
    {
        $this->vehicle = $vehicle;
        $this->responseUtil = $responseUtil;
    }

    public function create(Request $request)
    {
        $data = $this->vehicle->create($request);
        if ($data['code'] != '200') {
            return $this->responseUtil->sendError($data['message'], $data['code']);
        }
        return $this->responseUtil->sendResponse('Created', $data['data']);
    }

    public function update($id, Request $request)
    {
        $data = $this->vehicle->update($id, $request);
        if ($data['code'] != '200') {
            return $this->responseUtil->sendError($data['message'], $data['code']);
        }
        return $this->responseUtil->sendResponse('Updated', $data['data']);
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
