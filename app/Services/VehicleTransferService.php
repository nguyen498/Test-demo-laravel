<?php

namespace App\Services;

use App\Repositories\VehicleTranferRepository;
use App\Repositories\VehicleTransferDetailRepository;

class VehicleTransferService
{
    protected $vehicleTransferRepository;
    protected $vehicleTransferDetailRepository;

    public function __construct(VehicleTranferRepository $vehicleTransferRepository,
                                VehicleTransferDetailRepository $vehicleTransferDetailRepository)
    {
        $this->vehicleTransferRepository = $vehicleTransferRepository;
        $this->vehicleTransferDetailRepository = $vehicleTransferDetailRepository;
    }

    public function create()
    {

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
