<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Vehicle;
use App\Repositories\MediaRepository;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class VehicleService
{
    protected $vehicleRepository;
    protected $mediaRepository;

    public function __construct(VehicleRepository $vehicleRepository,
                                MediaRepository $mediaRepository)
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
            'cover_media' => 'required | mimes: jpg, png, jpeg',
            'detail_media' => 'required | mimes: jpg, png, jpeg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }

        if (!empty($request->has('reference'))) {
            $check = Vehicle::where('reference', $request->input('reference'))->first();
            if ($check) {
                return response()->json(
                    [
                        'message' => 'Reference is empty',
                    ], 401
                );
            } else {
                $vehicle = $this->vehicleRepository->create($request->all());
                $vehicle->vehicleRepository->update([
                    $vehicle->use_period = Carbon::now()->addYears(1),
                    $vehicle->user_id = 1,
                    $vehicle->reference = $request->input('reference'),
                ]);
            }
        } else {
            $vehicle = $this->vehicleRepository->create($request->all());
            $vehicle->vehicleRepository->update([
                $vehicle->use_period = Carbon::now()->addYears(1),
                $vehicle->user_id = 1,
                $vehicle->reference = rand(),
            ]);
        }
        if ($file = $request->file('cover_media')) {
            if (count(array($request->cover_media)) > 1) {
                return response()->json(
                    [
                        'message' => 'Cover media only one',
                    ], 401
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
            if (count(array($request->detail_media)) > 5) {
                return response()->json(
                    [
                        'message' => 'Detail media dont more than 5',
                    ], 401
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

        if (!empty($request->has('reference'))) {
            $check = Vehicle::where('reference', $request->input('reference'))->first();
            if ($check) {
                return response()->json(
                    [
                        'message' => 'Reference is empty',
                    ], 401
                );
            } else {
                $vehicle = $this->vehicleRepository->update($id, $request->all());
                $vehicle->use_period = Carbon::now()->addYears(1);
                $vehicle->user_id = 1;
                $vehicle->reference = $request->input('reference');
            }
        }

        $vehicle = Vehicle::find($id);
        if ($file = $request->file('cover_media')) {
            if (count(array($request->cover_media)) > 1) {
                return response()->json(
                    [
                        'message' => 'Cover media only one',
                    ], 401
                );
            }
            $media = $vehicle->medias()->where('type', '=', 1)->get();
            $vehicle->medias()->delete();

            if (File::exists(public_path() . '/upload/' . $media)) {
                File::delete(public_path() . '/upload/' . $media);
            }

            $request->cover_media->store(public_path() . '/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);

            $media = $this->mediaRepository->create($name);
            $media->type = 1;
            $vehicle->medias()->save($media);
        }
        if ($request->file('detail_media')) {
            if (count(array_filter($request->detail_media)) > 5) {
                return response()->json(
                    [
                        'message' => 'Detail media dont more than 5',
                    ], 401
                );
            }
            $data = [];
            $detailmedia = $vehicle->medias()->where('type', '=', 2)->get();
            if (File::exists(public_path() . '/upload/' . $detailmedia)) {
                File::delete(public_path() . '/upload/' . $detailmedia);
            }

            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);

                $media = $this->mediaRepository->create($name);
                $media->type = 2;
                $vehicle->medias()->save($media);
                $data[$key] = $media;
            }
        }
        return $vehicle;
    }

    public function delete($id)
    {
        return $this->vehicleRepository->delete($id);
    }

    public function search($inputs)
    {
        return $this->vehicleRepository->search($inputs);
    }
}
