<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\VehicleStation;
use Illuminate\Http\Request;

class StationRepository
{
    public function create(Request $request){
        $station = new VehicleStation();
        $station->name = $request->input('name');
        $station->phone = $request->input('phone');
        $station->save();
        if($file = $request->file('cover_media')){
            $request->cover_media->store('public/upload');

            $name = rand().'.'.$file->getClientOriginalName();
            $file->move(public_path().'upload', $name);
            $media = new Media();
            $media->path = $name;
            $media->type = 1;

            $station->medias()->save($media);
        }

        $media->mediaable()->save($station);
        $data = [];
        if($request->file('detail_media')){
            foreach ($request->file('detail_media') as $key=>$file){
                $name = rand().'.'.$file->getClientOriginalName();
                $file->move(public_path().'upload', $name);
                $media = new Media();
                $media->path = $name;
                $media->type = 2;
                $media->save();
                $station->medias()->save($media);
                $media->mediaable()->save($station);
                $data[$key] = $media;
            }
        }
        return $station;
    }

    public function  update($id, Request $request){
        $station = VehicleStation::findOrFail($id);
        $station->update([
            $station->name = $request->input('name'),
            $station->phone = $request->input('phone'),
        ]);
        return $station;
    }

    public function delete($id){
        $station = VehicleStation::findOrFail($id);
        return $station->delete();
    }

    public function search($kw){
        $station = VehicleStation::where('name', 'like', '%'.$kw.'%')->paginate(2);
        return $station;
    }
}
