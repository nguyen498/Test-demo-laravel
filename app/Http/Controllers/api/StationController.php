<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\VehicleStationResource;
use App\Services\StationService;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StationController extends Controller
{
    //
    protected $stationServices;
    protected $responseUtil;

    public function __construct(StationService $stationService,
                                ResponseUtil $responseUtil)
    {
        $this->stationServices = $stationService;
        $this->responseUtil = $responseUtil;
    }

    public function create(Request $request)
    {
        $data = $this->stationServices->create($request);
        if ($data['code'] != '200') {
            return $this->responseUtil->sendError($data['message'], $data['code']);
        }
        return $this->responseUtil->sendResponse('Created', $data['data']);
    }

    public function update($id, Request $request)
    {
        $data = $this->stationServices->update($id, $request);
        if ($data['code'] != '200') {
            return $this->responseUtil->sendError($data['message'], $data['code']);
        }
        return $this->responseUtil->sendResponse('Updated', $data['data']);
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
        $data = $this->stationServices->search($request->all());
        if ($data['code'] != '200') {
            return $this->responseUtil->sendError($data['message'], $data['code']);
        }
        return $this->responseUtil->sendResponse('Searched', $data['data']);
    }
}
