<?php

namespace App\Services;

use App\Models\Media;
use App\Models\VehicleStation;
use App\Repositories\MediaRepository;
use App\Repositories\StationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class StationService extends BaseService
{
    protected $stationRepository;
    protected $mediaRepository;

    public function __construct(StationRepository $stationRepository,
                                MediaRepository   $mediaRepository)
    {
        $this->stationRepository = $stationRepository;
        $this->mediaRepository = $mediaRepository;
    }

    public function create(Request $request)
    {
        $checkInput = $this->checkInput($request, null);
        if ($checkInput['is_fail']) {
            return $checkInput;
        }

        $station = $this->stationRepository->create(new VehicleStation(), $request->all());
        $reference = $request->input('reference');
        if (empty($reference)) {
            $reference = rand();
        }
        $this->stationRepository->update(new VehicleStation(), $station->id, [
            'reference' => $reference,
        ]);
        if ($request->hasFile('cover_media')) {
            $media = $this->saveCoverMedia($request);
            if (isset($media)) {
                $station->medias()->save($media);
            }
        }

        $data = [];
        if ($request->file('detail_media')) {
            $this->saveDetailMedia($request, $station, []);
        }

        return [
            'code' => '200',
            'data' => $station,
            'medias' => $data
        ];
    }

    public function update($id, Request $request)
    {
        $checkInput = $this->checkInput($request, $id);
        if ($checkInput['is_fail']) {
            return $checkInput;
        }
        $station = $this->stationRepository->update(new VehicleStation(), $id, $request->all());
        $reference = $request->input('reference');
        $this->stationRepository->update(new VehicleStation(), $station->id, [
            'reference' => $reference,
        ]);

        $station = $this->stationRepository->findId(new VehicleStation(), $id, [])->first();
        if ($request->file('cover_media')) {

            $station->medias()->delete();$media = $this->saveCoverMedia($request);
            if (isset($media)) {
                $station->medias()->save($media);
            }
        }
        $data = [];
        if ($request->file('detail_media')) {
            $station->detail_medias()->delete();
            if ($request->hasFile('detail_media')) {
                $data = $this->saveDetailMedia($request, $station, []);
            }
        }

        return [
            'code' => '200',
            'data' => $station,
            'medias' => $data
        ];
    }

    public function delete($id)
    {
        return $this->stationRepository->delete(new VehicleStation(), $id);
    }

    public function search($inputs)
    {
        return $this->stationRepository->search(new VehicleStation(), $inputs);
    }

    public function checkInput($request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required | numeric | digits:11',
            'cover_media' => 'required|mimes:jpg,jpeg,png,bmp|max:20000',
            'detail_media.*' => 'required|mimes:jpg,jpeg,png,bmp|max:20000'
        ]);
        if ($validate->fails()) {
            return [
                'is_fail' => true,
                'code' => '001',
                'message' => $validate->errors(),
            ];
        }
        if (!empty($request->reference)) {
            $input_check = ['reference' => $request->input('reference')];
            if (isset($id)) {
                $input_check['id'] = $id;
            }
            $check = $this->stationRepository->check(new VehicleStation(), 'reference', $input_check);
            if ($check) {
                return ([
                    'is_fail' => true,
                    'code' => '002',
                    'message' => 'Reference is duplicate',
                ]);
            }
        }
        if ($request->hasFile('cover_media')) {
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
        return ['is_fail' => false];
    }
}
