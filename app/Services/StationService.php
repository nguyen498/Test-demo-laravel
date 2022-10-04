<?php

namespace App\Services;

use App\Repositories\StationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StationService
{
    protected $stationRepository;
    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }

    public function create(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone'=> 'required | numeric | digits:11',
//            'cover_media' => 'required | mimes: jpg, png, jpeg',
//            'detail_media' => 'required | mimes: jpg, png, jpeg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }

        return $this->stationRepository->create($request);
    }

    public function  update($id, Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone'=> 'required | numeric | digits:11',
//            'cover_media' => 'required | mimes: jpg, png, jpeg',
//            'detail_media' => 'required | mimes: jpg, png, jpeg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
        return $this->stationRepository->update($id, $request);
    }

    public function delete($id){
        return $this->stationRepository->delete($id);
    }

    public function search($kw){
        return $this->stationRepository->search($kw);
    }
}
