<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\VehicleStationResource;
use App\Services\StationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StationController extends Controller
{
    //
    protected $stationServices;

    public function __construct(StationService $stationService)
    {
        $this->stationServices = $stationService;
    }

    public function create(Request $request)
    {
        $data = $this->stationServices->create($request);
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
        $data = $this->stationServices->update($id, $request);
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
        $this->stationServices->delete($id);
        return
            response()->json([
                'message' => 'delete success'
            ]);
    }

    public function search(Request $request)
    {
        $station = $this->stationServices->search($request->all());
        return VehicleStationResource::collection($station);
//            response()->json([
//            'data' => $station
//        ]);
    }
}
