<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleRepository
{
    public function create(Request $request)
    {
        $vehicle = new Vehicle;
        $vehicle->name = $request->input('name');
        $vehicle->vehicle_number = $request->input('vehicle_number');
        $vehicle->price = $request->input('price');
        $vehicle->use_period = Carbon::now()->addYears(1);
        $vehicle->user_id = 1;
        $vehicle->save();
        if($file = $request->file('cover_media')){
           $request->cover_media->store('public/upload');

            $name = rand().'.'.$file->getClientOriginalName();
            $file->move(public_path().'upload', $name);
            $media = new Media();
            $media->path = $name;
            $media->type = 1;

            $vehicle->medias()->save($media);
        }
        $media->mediaable()->save($vehicle);
        $data = [];
        if($request->file('detail_media')){
            foreach ($request->file('detail_media') as $key=>$file){
                $name = rand().'.'.$file->getClientOriginalName();
                $file->move(public_path().'upload', $name);
                $media = new Media();
                $media->path = $name;
                $media->type = 2;
                $media->save();
                $vehicle->medias()->save($media);
                $media->mediaable()->save($vehicle);
                $data[$key] = $media;
            }
        }

        return $vehicle;
    }

    public function update($id, Request $request)
    {
        $vehicle = Vehicle::findOrFail($id);
//        $vehicle->update([
//            $vehicle->name = $request->input('name'),
//            $vehicle->vehicle_number = $request->input('vehicle_number'),
//            $vehicle->price = $request->input('price'),
//            $vehicle->user_id = 1
//        ]);
        $vehicle->name = $request->input('name');
        $vehicle->vehicle_number = $request->input('vehicle_number');
        $vehicle->price = $request->input('price');
        $vehicle->save();

        if($file = $request->file('cover_media')){
            $destination = 'public/upload/'.$vehicle->medias->where('type', '=', 1)->get();
            if(Media::exists($destination)){
                Media::delete($destination);
            }
            $name = rand().'.'.$file->getClientOriginalName();
            $file->move(public_path().'upload', $name);
            $media = new Media();
            $media->path = $name;
            $media->type = 1;

            $vehicle->medias()->save($media);
        }
//        $media->mediaable()->update($vehicle);
        $data = [];
        if($request->file('detail_media')){
            foreach ($request->file('detail_media') as $key=>$file){
                $name = rand().'.'.$file->getClientOriginalName();
                $file->move(public_path().'upload', $name);
                $media = new Media();
                $media->path = $name;
                $media->type = 2;
                $media->save();
                $vehicle->medias()->save($media);
                $media->mediaable()->save($vehicle);
                $data[$key] = $media;
            }
        }
        return $vehicle;
    }

    public function delete($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return $vehicle->delete();
    }

    public function search($kw)
    {
        $vehicle = Vehicle::where('name', 'like', '%'.$kw.'%')->paginate(2);
        return $vehicle;
    }
}
