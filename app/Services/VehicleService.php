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

class VehicleService extends BaseService
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

        $checkInput = $this->checkInput($request->all());
        if ($checkInput['is_fail']) {
            return $checkInput;
        }

        if (!empty($request->has('reference'))) {
            $check = $this->vehicleRepository->check(new Vehicle(), 'reference',
                ['reference' => $request->input('reference')]);
            if ($check) {
                return ([
                    'code' => '002',
                    'message' => 'Reference is duplicate',
                ]);
            } else {
                $vehicle = $this->vehicleRepository->create(new Vehicle(), $request->all());
                $this->vehicleRepository->update(new Vehicle(), $vehicle->id, [
                    'use_period' => Carbon::now()->addYears(1),
                    'user_id' => 1,
                    'reference' => $request->input('reference'),
                ]);
            }
        } else {
            $vehicle = $this->vehicleRepository->create(new Vehicle(), $request->all());
            $this->vehicleRepository->update(new Vehicle(), $vehicle->id, [
                'use_period' => Carbon::now()->addYears(1),
                'user_id' => 1,
                'reference' => rand(),
            ]);
        }
        if ($file = $request->file('cover_media')) {

            $checkCoverMedia = $this->checkMedia($request->input('cover_media'), 1);
            if ($checkCoverMedia['is_fail']) {
                return $checkCoverMedia;
            }

            $request->cover_media->store(public_path() . '/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);

            $media = $this->mediaRepository->create( new Media(), [
                'name' => $name,
                'path' => '/upload/' . $name,
                'type' => 1
            ]);

            $vehicle->medias()->save($media);
        }
        $media->mediaable()->save($vehicle);
        $data = [];
        if ($request->file('detail_media')) {

            $checkDetailMedia = $this->checkMedia($request->input('detail_media'), 5);
            if ($checkDetailMedia['is_fail']) {
                return $checkDetailMedia;
            }

            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);
                $media = $this->mediaRepository->create( new Media(), [
                    'name' => $name,
                    'path' => '/upload/' . $name,
                    'type' => 2
                ]);
                $vehicle->medias()->save($media);
                $media->mediaable()->save($vehicle);
                $data[$key] = $media;
            }
        }

        return [
            'code' => '200',
            'data' => $vehicle
        ];
    }

    public function update($id, Request $request)
    {
        $checkInput = $this->checkInput($request->all());
        if ($checkInput['is_fail']) {
            return $checkInput;
        }

        if (!empty($request->has('reference'))) {
            $check = $this->vehicleRepository->check(new Vehicle(), 'reference', [
                'reference' => $request->input('reference'),
                'id' => $request->input('id')
            ]);
            if ($check) {
                return ([
                    'code' => '004',
                    'message' => 'Reference is duplicate',
                ]);
            } else {
                $this->vehicleRepository->update(new Vehicle(), $id, $request->all());
                $this->vehicleRepository->update(new Vehicle(), $id, [
                    'use_period' => Carbon::now()->addYears(1),
                    'user_id' => 1,
                    'reference' => $request->input('reference')
                ]);
            }
        }

        $vehicle = $this->vehicleRepository->findId(new Vehicle(), $id)->first();
        if ($file = $request->file('cover_media')) {
            $checkCoverMedia = $this->checkMedia($request->input('cover_media'), 1);
            if ($checkCoverMedia['is_fail']) {
                return $checkCoverMedia;
            }
            $media = $vehicle->medias()->get();
            $vehicle->medias()->delete();

            if (File::exists(public_path() . '/upload/' . $media)) {
                File::delete(public_path() . '/upload/' . $media);
            }

            $request->cover_media->store(public_path() . '/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);

            $media = $this->mediaRepository->create( new Media(), [
                'name' => $name,
                'path' => '/upload/' . $name,
                'type' => 1
            ]);
            $vehicle->medias()->save($media);
        }
        if ($request->file('detail_media')) {
            $checkDetailMedia = $this->checkMedia($request->input('detail_media'), 5);
            if ($checkDetailMedia['is_fail']) {
                return $checkDetailMedia;
            }

            $data = [];
            $detailmedia = $vehicle->detail_medias()->get();
            if (File::exists(public_path() . '/upload/' . $detailmedia)) {
                File::delete(public_path() . '/upload/' . $detailmedia);
            }

            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);

                $media = $this->mediaRepository->create( new Media(), [
                    'name' => $name,
                    'path' => '/upload/' . $name,
                    'type' => 2
                ]);
                $vehicle->detail_medias()->save($media);
                $data[$key] = $media;
            }
        }
        return [
            'code' => '200',
            'data' => $vehicle
        ];
    }

    public function delete($id)
    {
        return $this->vehicleRepository->delete(new Vehicle(), $id);
    }

    public function search($inputs)
    {
        return $this->vehicleRepository->search(new Vehicle(), $inputs);
    }

    public function checkInput(array $input)
    {
        $validate = Validator::make($input, [
            'name' => 'required',
            'vehicle_number' => 'required',
            'price' => 'required | numeric | min:0',
            'cover_media' => 'required|mimes:jpg,jpeg,png,bmp|max:20000',
            'detail_media.*' => 'required|mimes:jpg,jpeg,png,bmp|max:20000'
        ]);
        if ($validate->fails()) {
            return (
            [
                'is_fail' => true,
                'code' => '001',
                'message' => $validate->errors(),
            ]
            );
        }
        return [
            'is_fail' => false,
        ];
    }

}
