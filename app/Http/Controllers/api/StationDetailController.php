<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\VehicleStationDetailResource;
use App\Services\StationDetailService;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StationDetailController extends Controller
{
    //
    protected $stationDetailService;
    protected $responseUtil;

    public function __construct(StationDetailService $stationDetailService,
                                ResponseUtil $responseUtil)
    {
        $this->stationDetailService = $stationDetailService;
        $this->responseUtil = $responseUtil;
    }

    public function create(Request $request)
    {
        $data = $this->stationDetailService->create($request);
        if ($data['code'] != '200') {
            return $this->responseUtil->sendError($data['message'], $data['code']);
        }
        return $this->responseUtil->sendResponse('Created', $data['data']);
    }

    public function update($id, Request $request)
    {
        $data = $this->stationDetailService->update($id, $request);
        if ($data['code'] != '200') {
            return $this->responseUtil->sendError($data['message'], $data['code']);
        }
        return $this->responseUtil->sendResponse('Updated', $data['data']);
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
    }
}
