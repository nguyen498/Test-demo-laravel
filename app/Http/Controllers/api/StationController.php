<?php

namespace App\Http\Controllers\api;

use App\Services\StationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StationController extends Controller
{
    //
    protected $stationServices;
    public function __construct(StationService $stationService){
        $this->stationServices = $stationService;
    }

    public function create(Request $request){
        $data = $this->stationServices->create($request);
        return response()->json([
            'data' => $data,
        ], 201);
    }

    public function  update(){

    }

    public function delete(){

    }

    public function search(){

    }
}
