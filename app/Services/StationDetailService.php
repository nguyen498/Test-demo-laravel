<?php

namespace App\Services;

use App\Repositories\StationDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StationDetailService
{
    protected $stationDetailRepository;

    public function __construct(StationDetailRepository $stationDetailRepository)
    {
        $this->stationDetailRepository = $stationDetailRepository;
    }

    public function create(Request $request){
        $validate = Validator::make($request->all(), [
            'vehicle_station_id'=> 'required ',
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
        return $this->stationDetailRepository->create($request->all());

    }

    public function update($id, Request $request){
        $validate = Validator::make($request->all(), [
            'vehicle_station_id'=> 'required ',
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
        return $this->stationDetailRepository->update($id, $request->all());
    }

    public function delete($id){
        return $this->stationDetailRepository->delete($id);
    }

    public function search($kw){
        return $this->stationDetailRepository->search($kw);
    }
}
