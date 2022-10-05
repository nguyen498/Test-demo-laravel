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
        return new VehicleStationResource($data);
//            response()->json([
//                'data' => $data,
//            ]);
    }

    public function update($id, Request $request)
    {
        $data = $this->stationServices->update($id, $request);
        return new VehicleStationResource($data);
//            response()->json([
//                'data' => $data,
//            ]);
    }

    public function delete($id)
    {
        $this->stationServices->delete($id);
        return
            response()->json([
                'message' => 'delete success'
            ]);
    }

    public function search($kw)
    {
        $station = $this->stationServices->search($kw);
        return VehicleStationResource::collection($station);
//            response()->json([
//            'data' => $station
//        ]);
    }
}
