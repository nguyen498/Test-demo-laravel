<?php

namespace App\Services;

use App\Models\Media;
use App\Models\VehicleStation;
use App\Repositories\MediaRepository;
use App\Repositories\StationRepository;
use Illuminate\Http\Request;
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
        $checkInput = $this->checkInput($request->all());
        if ($checkInput['is_fail']) {
            return $checkInput;
        }

        if (!empty($request->has('reference'))) {
            $check = $this->stationRepository->check(new VehicleStation(), 'reference',
                ['reference' => $request->input('reference')]);
            if ($check) {
                return ([
                    'code' => '002',
                    'message' => 'Reference is duplicate',
                ]);
            } else {
                $station = $this->stationRepository->create(new VehicleStation(), $request->all());
                $station->update([$station->reference = $request->input('reference')]);
            }
        } else {
            $station = $this->stationRepository->create(new VehicleStation(), $request->all());
            $station->update([$station->reference = rand()]);
        }
        if ($file = $request->file('cover_media')) {
            $checkCoverMedia = $this->checkMedia($request->input('cover_media'), 1);
            if ($checkCoverMedia['is_fail']) {
                return $checkCoverMedia;
            }
            $request->cover_media->store(public_path() . '/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);
            $media = $this->mediaRepository->create(new Media(), [
                'name' => $name,
                'path' => '/upload/' . $name,
                'type' => 2
            ]);

            $station->medias()->save($media);
        }

        $media->mediaable()->save($station);
        $data = [];
        if ($request->file('detail_media')) {
            $checkDetailMedia = $this->checkMedia($request->input('detail_media'), 5);
            if ($checkDetailMedia['is_fail']) {
                return $checkDetailMedia;
            }

            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);
                $media = $this->mediaRepository->create(new Media(), [
                    'name' => $name,
                    'path' => '/upload/' . $name,
                    'type' => 2
                ]);
                $station->medias()->save($media);
                $data[$key] = $media;
            }
        }

        return [
            'code' => '200',
            'data' => $station
        ];
    }

    public function update($id, Request $request)
    {
        $checkInput = $this->checkInput($request->all());
        if ($checkInput['is_fail']) {
            return $checkInput;
        }
        if (!empty($request->has('phone'))) {
            $check = $this->stationRepository->check(new VehicleStation(), 'phone', [
                'phone' => $request->input('phone'),
                'id' => $request->input('id')
            ]);
            if ($check) {
                return ([
                    'code' => '002',
                    'message' => 'Phone is duplicate',
                ]);
            } else {
                $station = $this->stationRepository->update(new VehicleStation(), $id, $request->all());
                $station->update([$station->reference = $request->input('reference')]);
            }
        }

        $station = $this->stationRepository->findId(new VehicleStation(), $id)->first();
        if ($file = $request->file('cover_media')) {
            $checkCoverMedia = $this->checkMedia($request->input('cover_media'), 1);
            if ($checkCoverMedia['is_fail']) {
                return $checkCoverMedia;
            }
            $media = $station->medias()->get();
            $station->medias()->delete();

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
            $station->medias()->save($media);
        }
        if ($request->file('detail_media')) {
            $checkDetailMedia = $this->checkMedia($request->input('detail_media'), 5);
            if ($checkDetailMedia['is_fail']) {
                return $checkDetailMedia;
            }
            $data = [];
            $detailmedia = $station->detail_medias()->get();
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
                $station->medias()->save($media);
                $data[$key] = $media;
            }
        }

        return [
            'code' => '200',
            'data' => $station
        ];
    }

    public function checkInput($inputs)
    {
        $validate = Validator::make($inputs, [
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
        return ['is_fail' => false];
    }

}
