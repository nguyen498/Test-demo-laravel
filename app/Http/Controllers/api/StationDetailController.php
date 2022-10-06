<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\VehicleStationDetailResource;
use App\Services\StationDetailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StationDetailController extends Controller
{
    //
    protected $stationDetailService;

    public function __construct(StationDetailService $stationDetailService)
    {
        $this->stationDetailService = $stationDetailService;
    }

    public function create(Request $request)
    {
        $data = $this->stationDetailService->create($request);
        return
//            new VehicleStationDetailResource($data);
            response()->json([
           'data'=>$data
        ]);
    }

    public function update($id, Request $request)
    {
        $data = $this->stationDetailService->update($id, $request);
        return
//            new VehicleStationDetailResource($data);
            response()->json([
            'data'=>$data
        ]);
    }

    public function delete($id)
    {
        $this->stationDetailService->delete($id);
        return response()->json([
            'message' => 'delete success'
        ]);
    }

    public function search($kw)
    {
        $station = $this->stationDetailService->search($kw);
        return VehicleStationDetailResource::collection($station);
//            response()->json([
//            'data' => $station
//        ]);
    }
}
