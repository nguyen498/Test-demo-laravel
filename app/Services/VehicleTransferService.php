<?php

namespace App\Services;

use App\Repositories\VehicleTranferRepository;
use App\Repositories\VehicleTransferDetailRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class VehicleTransferService
{
    protected $vehicleTransferRepository;
    protected $vehicleTransferDetailRepository;

    public function __construct(VehicleTranferRepository        $vehicleTransferRepository,
                                VehicleTransferDetailRepository $vehicleTransferDetailRepository)
    {
        $this->vehicleTransferRepository = $vehicleTransferRepository;
        $this->vehicleTransferDetailRepository = $vehicleTransferDetailRepository;
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'vehicle_number' => 'required',
            'price' => 'required | numeric',
            'cover_media.*' => 'required | mimes: jpg, png, jpeg',
            //'detail_media.*' => 'required | mimes: jpg, png, jpeg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function search()
    {

    }
}
