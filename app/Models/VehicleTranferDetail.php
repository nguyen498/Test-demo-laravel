<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTranferDetail extends Model
{
    protected $fillable = [
        'reference',
        'vehicle_transfer_id',
        'vehicle_station_detail_id',
        'vehicle_id',
        'type',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'reference' => 'string',
        'vehicle_transfer_id' => 'integer',
        'vehicle_station_detail_id' => 'integer',
        'vehicle_id' => 'integer',
        'type' => 'integer',
        'status' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    //
    public function vehicleTranfer()
    {
        return $this->belongsTo('App\Models\VehicleTranfer', 'vehicle_tranfer_id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id');
    }

    public function vehicle_station_detail()
    {
        return $this->belongsTo('App\Models\VehicleStationDetail', 'vehicle_station_id');
    }
}
