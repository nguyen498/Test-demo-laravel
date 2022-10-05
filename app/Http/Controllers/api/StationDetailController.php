<?php

namespace App\Http\Controllers\api;

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

    public function create(){

    }

    public function update(){

    }

    public function delete(){

    }

    public function search(){

    }
}
