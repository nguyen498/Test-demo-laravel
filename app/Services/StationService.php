<?php

namespace App\Services;

use App\Models\Media;
use App\Repositories\StationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StationService
{
    protected $stationRepository;
    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }

    public function create(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone'=> 'required | numeric | digits:11',
//            'cover_media' => 'required | mimes: jpg, png, jpeg',
//            'detail_media' => 'required | mimes: jpg, png, jpeg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
        $station = $this->stationRepository->create($request->all());
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

        return $this->stationRepository->create($request->all());
    }

    public function  update($id, Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone'=> 'required | numeric | digits:11',
//            'cover_media' => 'required | mimes: jpg, png, jpeg',
//            'detail_media' => 'required | mimes: jpg, png, jpeg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }


        return $this->stationRepository->update($id, $request->all());
    }

    public function delete($id){
        return $this->stationRepository->delete($id);
    }

    public function search($kw){
        return $this->stationRepository->search($kw);
    }
}
