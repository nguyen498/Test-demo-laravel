<?php

namespace App\Services;

use App\Models\Media;
use App\Models\VehicleStationDetail;
use App\Repositories\MediaRepository;
use App\Repositories\StationDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class StationDetailService
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
        $validate = Validator::make($request->all(), [
            'vehicle_station_id' => 'required ',
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
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
                $stationDetail = $this->stationDetailRepository->create($request->all());
                $stationDetail->update([$stationDetail->reference = $request->input('reference')]);
            }
        } else {
            $stationDetail = $this->stationDetailRepository->create($request->all());
            $stationDetail->update([$stationDetail->reference = rand()]);
        }
        if ($file = $request->file('cover_media')) {
            if (count(array($request->cover_media)) > 1) {
                return response()->json(
                    [
                        'message' => 'Cover media only one',
                    ]
                );
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
            if (count(array($request->detail_media)) > 5) {
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
                $stationDetail->medias()->save($media);
                $media->mediaable()->save($stationDetail);
                $data[$key] = $media;
            }
        }
        return $stationDetail;

    }

    public function update($id, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'vehicle_station_id' => 'required ',
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
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
                $stationDetail = $this->stationDetailRepository->update($id, $request->all());
                $stationDetail->update([$stationDetail->reference = $request->input('reference')]);
            }
        }

        $stationDetail = VehicleStationDetail::find($id);
        if ($file = $request->file('cover_media')) {
            if (count(array($request->cover_media)) > 1) {
                return response()->json(
                    [
                        'message' => 'Cover media only one',
                    ]
                );
            }
            $media = $stationDetail->medias()->where('type', '=', 1)->get();
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
        return $this->stationDetailRepository->delete($id);
    }

    public function search(array $inputs)
    {
        return $this->stationDetailRepository->search($inputs);
    }
}
