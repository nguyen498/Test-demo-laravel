<?php

namespace App\Http\Controllers\api;

use App\Repositories\VehicleRepository;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    //

    protected $vehicle;

    public function __construct(VehicleRepository $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function create(Request $request){
        $data = $this->vehicle->create($request);
        return response()->json([
            'data' => $data
        ], 201);
    }
}
