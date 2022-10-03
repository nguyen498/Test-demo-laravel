<?php

namespace App\Repositories;

use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleRepository
{
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'vehicle_number' => 'required',
            'price' => 'required | numeric'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
//        $vehicle = Vehicle::create([
//            'user_id'=> $request->user()->id,
//            'name' => $request->input('name'),
//            'reference' => $request->input('reference'),
//            'vehicle_number' => $request->input('vehicle_number'),
//            'description' => $request->input('description'),
//            'use_period' => $request->input('use_period'),
//            'price' => $request->input('price'),
//            'status' => $request->input('status'),
//        ]);
        $vehicle = new Vehicle;
        $vehicle->name = $request->input('name');
        $vehicle->vehicle_number = $request->input('vehicle_number');
        $vehicle->price = $request->input('price');
        $vehicle->use_period = Carbon::now()->addYears(1);
        $vehicle->user_id = 1;
        $vehicle->save();
        return $vehicle;
//        return response()->json([
//            'data' => $vehicle
//        ], 201
//        );
    }

    public function update($id)
    {
        $vehicle =  Vehicle::findOrFail($id);
    }

    public function delete($id)
    {

    }

    public function search($kw)
    {

    }

    public function  addCoverMedia($path){

    }

    public function  addDetailMedia($path){

    }
}
