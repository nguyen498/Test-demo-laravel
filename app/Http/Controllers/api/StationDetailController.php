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
        $data = $this->stationDetailService->update($id, $request);
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
        $this->stationDetailService->delete($id);
        return response()->json([
            'message' => 'delete success'
        ]);
    }

    public function search(Request $request)
    {
        $stationDetails = $this->stationDetailService->search($request->all());
        return VehicleStationDetailResource::collection($stationDetails);
//            response()->json([
//            'data' => $station
//        ]);
    }
}
