<?php

namespace App\Services;

use App\Models\Media;
use App\Models\VehicleStationDetail;
use App\Repositories\MediaRepository;
use App\Repositories\StationDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class StationDetailService extends BaseService
{
    protected $stationDetailRepository;
    protected $mediaRepository;

    public function __construct(StationDetailRepository $stationDetailRepository,
                                MediaRepository         $mediaRepository)
    {
        $this->stationDetailRepository = $stationDetailRepository;
        $this->mediaRepository = $mediaRepository;
    }

    public function create(Request $request)
    {
        $checkInput = $this->checkInput($request->all());
        if ($checkInput['is_fail']) {
            return $checkInput;
        }
        $stationDetail = $this->stationDetailRepository->create(new VehicleStationDetail(), $request->all());
        if ($request->file('cover_media')) {
            $media = $this->saveCoverMedia($request);
            if (isset($media)) {
                $stationDetail->medias()->save($media);
            }
        }
        $data = [];
        if ($request->file('detail_media')) {
            $this->saveDetailMedia($request, $stationDetail, []);
        }
        return [
            'code' => '200',
            'data' => $stationDetail,
            'medias' => $data
        ];

    }

    public function update($id, Request $request)
    {
        $checkInput = $this->checkInput($request->all());
        if ($checkInput['is_fail']) {
            return $checkInput;
        }

        $stationDetail = VehicleStationDetail::find($id);
        if ($file = $request->file('cover_media')) {
            $checkCoverMedia = $this->checkMedia($request->input('cover_media'), 1);
            if ($checkCoverMedia['is_fail']) {
                return $checkCoverMedia;
            }
            $media = $stationDetail->medias()->where('type', '=', 1)->first();
            $stationDetail->medias()->delete();

            if (File::exists(public_path() . '/upload/' . $media)) {
                File::delete(public_path() . '/upload/' . $media);
            }

            $request->cover_media->store(public_path() . '/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);

            $media = $this->mediaRepository->create(new Media(), [
                'name' => $name,
                'path' => '/upload/' . $name,
                'type' => 1
            ]);
            $media->type = 1;
            $stationDetail->medias()->save($media);
        }
        if ($request->file('detail_media')) {
            $checkDetailMedia = $this->checkMedia($request->input('detail_media'), 5);
            if ($checkDetailMedia['is_fail']) {
                return $checkDetailMedia;
            }
            $data = [];
            $detailmedia = $stationDetail->medias()->where('type', '=', 2)->get();
            if (File::exists(public_path() . '/upload/' . $detailmedia)) {
                File::delete(public_path() . '/upload/' . $detailmedia);
            }

            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);

                $media = $this->mediaRepository->create(new Media(), [
                    'name' => $name,
                    'path' => '/upload/' . $name,
                    'type' => 2
                ]);
                $media->type = 2;
                $stationDetail->medias()->save($media);
                $data[$key] = $media;
            }
        }
        return [
            'code' => '200',
            'data' => $stationDetail
        ];
    }

    public function delete($id)
    {
        return $this->stationDetailRepository->delete(new VehicleStationDetail(), $id);
    }

    public function search(array $inputs)
    {
        return $this->stationDetailRepository->search(new VehicleStationDetail(), $inputs);
    }

    public function checkInput($request)
    {
        $validate = Validator::make($request->all(), [
            'vehicle_station_id' => 'required ',
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'code' => '001',
                    'message' => $validate->errors(),
                ], 401
            );
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

    }
}
