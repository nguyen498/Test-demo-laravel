<?php

namespace App\Services;

use App\Repositories\VehicleRepository;

class VehicleService
{
    protected $vehicleRepository;

    public function __construct (VehicleRepository $vehicleRepository){
        $this->vehicleRepository = $vehicleRepository;
    }
}
