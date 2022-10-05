<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleStationDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'vehicle_station_id' => $this->vehicle_station_id,
            'vehicle_id' => $this->vehicle_id,
            'code' => $this->code,
            'floor' => $this->floor,
            'slot' => $this->slot,
            'area' => $this->area,
            'gate' => $this->gate,
            'status' => $this->status,
            'type' => $this->type,
            'medias' => $this->medias
        ];
    }
}
