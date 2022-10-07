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
        //check request
        $checkInput = $this->checkInput($request, null);
        if ($checkInput['is_fail']) {
            return $checkInput;
        }
        //create Vehicle
        $vehicle = $this->vehicleRepository->create(new Vehicle(), $request->all());
        $reference = $request->input('reference');
        if (empty($reference)) {
            $reference = rand();
        }
        $this->vehicleRepository->update(new Vehicle(), $vehicle->id, [
            'use_period' => Carbon::now()->addYears(1),
            'user_id' => 1,
            'reference' => $reference,
        ]);
        //save cover media
        if ($request->hasFile('cover_media')) {
            $media = $this->saveCoverMedia($request);
            if (isset($media)) {
                $vehicle->medias()->save($media);
            }
        }
        //save detail media
        if ($request->hasFile('detail_media')) {
            $this->saveDetailMedia($request, $vehicle, []);
        }
        $vehicle = $this->vehicleRepository->findId(new Vehicle(), $vehicle->id, ['medias', 'detail_medias']);
        return [
            'code' => '200',
            'data' => [
                'vehicle' => $vehicle,
            ]
        ];
    }

    public function update($id, Request $request)
    {
        $checkInput = $this->checkInput($request, $id);
        if ($checkInput['is_fail']) {
            return $checkInput;
        }
        $vehicle = $this->vehicleRepository->update(new Vehicle(), $id, $request->all());
        $reference = $request->input('reference');
        $this->vehicleRepository->update(new Vehicle(), $vehicle->id, [
            'reference' => $reference,
        ]);

        $vehicle = $this->vehicleRepository->findId(new Vehicle(), $id, [])->first();
        if ($request->file('cover_media')) {
            $vehicle->medias()->delete();
            $media = $this->saveCoverMedia($request);
            if (isset($media)) {
                $vehicle->medias()->save($media);
            }
        }
        $data = [];
        if ($request->file('detail_media')) {
            $vehicle->detail_medias()->delete();
            if ($request->hasFile('detail_media')) {
                $data = $this->saveDetailMedia($request, $vehicle, []);
            }
        }
        $vehicle = $this->vehicleRepository->findId(new Vehicle(), $vehicle->id, ['medias', 'detail_medias']);
        return [
            'code' => '200',
            'data' => [
                'vehicle' => $vehicle,
            ]
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

    public function checkInput($request, $id)
    {
        $validate = Validator::make($request->all(), [
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
        if (!empty($request->reference)) {
            $input_check = ['reference' => $request->input('reference')];
            if (isset($id)) {
                $input_check['id'] = $id;
            }
            $check = $this->vehicleRepository->check(new Vehicle(), 'reference', $input_check);
            if ($check) {
                return ([
                    'is_fail' => true,
                    'code' => '002',
                    'message' => 'Reference is duplicate',
                ]);
            }
        }

        if ($request->file('cover_media')) {
            $checkCoverMedia = $this->checkMedia($request->input('cover_media'), 1);
            if ($checkCoverMedia['is_fail']) {
                return $checkCoverMedia;
            }
        }

        if ($request->hasFile('detail_media')) {
            $checkDetailMedia = $this->checkMedia($request->input('detail_media'), 5);
            if ($checkDetailMedia['is_fail']) {
                return $checkDetailMedia;
            }
        }
        return [
            'is_fail' => false,
        ];
    }
}
