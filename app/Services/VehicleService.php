<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Vehicle;
use App\Repositories\MediaRepository;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class VehicleService
{
    protected $vehicleRepository;
    protected $mediaRepository;

    public function __construct(VehicleRepository $vehicleRepository,
                                MediaRepository   $mediaRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->mediaRepository = $mediaRepository;
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
        $vehicle = $this->vehicleRepository->create($request->all());
        $vehicle->use_period = Carbon::now()->addYears(1);
        $vehicle->user_id = 1;
        if ($file = $request->file('cover_media')) {
            if (count(array($request->cover_media)) > 1) {
                return response()->json(
                    [
                        'message' => 'Cover media only one',
                    ]
                );
            }
            $request->cover_media->store(public_path() . '/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);
            $media = new Media();
            $media->path = $name;
            $media->type = 1;

            $vehicle->medias()->save($media);
        }
        $media->mediaable()->save($vehicle);
        $data = [];
        if ($request->file('detail_media')) {
            if (count(array_filter($request->detail_media)) > 5) {
                return response()->json(
                    [
                        'message' => 'Detail media dont more than 5',
                    ]
                );
            }
            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);
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
        $vehicle = Vehicle::find($id);
        if($request->cover_media) {
            $inputs['cover_media'] = $request->cover_media->store(public_path() . '/upload');
            $vehicle->medias = $inputs['cover_media'];
            $vehicle->medias()->save($vehicle->medias);
        }
        return $this->vehicleRepository->update($id, $request->all());
    }

    public function delete($id)
    {
        return $this->vehicleRepository->delete($id);
    }

    public function search($kw)
    {
        return $this->vehicleRepository->search($kw);
    }
}
