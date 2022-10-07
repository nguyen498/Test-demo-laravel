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

        if (!empty($request->has('reference'))) {
            $check = VehicleStationDetail::where('reference', $request->input('reference'))->first();
            if ($check) {
                return response()->json(
                    [
                        'message' => 'Reference is empty',
                    ], 401
                );
            } else {
                $stationDetail = $this->stationDetailRepository->create(VehicleStationDetail::class,$request->all());
                $stationDetail->update([$stationDetail->reference = $request->input('reference')]);
            }
        } else {
            $stationDetail = $this->stationDetailRepository->create(VehicleStationDetail::class,$request->all());
            $stationDetail->update([$stationDetail->reference = rand()]);
        }
        if ($file = $request->file('cover_media')) {
            $checkCoverMedia = $this->checkMedia($request->input('cover_media'), 1);
            if ($checkCoverMedia['is_fail']) {
                return $checkCoverMedia;
            }
            $request->cover_media->store('public/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);
            $media = new Media();
            $media->path = $name;
            $media->type = 1;

            $stationDetail->medias()->save($media);
        }

        $media->mediaable()->save($stationDetail);
        $data = [];
        if ($request->file('detail_media')) {
            $checkDetailMedia = $this->checkMedia($request->input('detail_media'), 5);
            if ($checkDetailMedia['is_fail']) {
                return $checkDetailMedia;
            }
            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);
                $media = new Media();
                $media->path = $name;
                $media->type = 2;
                $media->save();
                $stationDetail->medias()->save($media);
                $media->mediaable()->save($stationDetail);
                $data[$key] = $media;
            }
        }
        return $stationDetail;

    }

    public function update($id, Request $request)
    {
        $checkInput = $this->checkInput($request->all());
        if ($checkInput['is_fail']) {
            return $checkInput;
        }
        if (!empty($request->has('reference'))) {
            $check = VehicleStationDetail::where('reference', $request->input('reference'))->first();
            if ($check) {
                return response()->json(
                    [
                        'message' => 'Reference is empty',
                    ], 401
                );
            } else {
                $stationDetail = $this->stationDetailRepository->update(VehicleStationDetail::class, $id, $request->all());
                $stationDetail->update([$stationDetail->reference = $request->input('reference')]);
            }
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

            $media = $this->mediaRepository->create($name);
            $media->type = 1;
            $stationDetail->medias()->save($media);
        }
        if ($request->file('detail_media')) {
            if (count(array_filter($request->detail_media)) > 5) {
                return response()->json(
                    [
                        'message' => 'Detail media dont more than 5',
                    ]
                );
            }
            $data = [];
            $detailmedia = $stationDetail->medias()->where('type', '=', 2)->get();
            if (File::exists(public_path() . '/upload/' . $detailmedia)) {
                File::delete(public_path() . '/upload/' . $detailmedia);
            }

            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);

                $media = $this->mediaRepository->create($name);
                $media->type = 2;
                $stationDetail->medias()->save($media);
                $data[$key] = $media;
            }
        }
        return $stationDetail;
    }

    public function delete($id)
    {
        return $this->stationDetailRepository->delete(VehicleStationDetail::class, $id);
    }

    public function search(array $inputs)
    {
        return $this->stationDetailRepository->search(VehicleStationDetail::class, $inputs);
    }

    public function checkInput(array $inputs){
        $validate = Validator::make($inputs, [
            'vehicle_station_id' => 'required ',
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
    }
}
