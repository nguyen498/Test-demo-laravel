<?php

namespace App\Services;

use App\Models\Media;
use App\Models\VehicleStation;
use App\Repositories\MediaRepository;
use App\Repositories\StationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class StationService
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
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required | numeric | digits:11',
//            'cover_media' => 'required | mimes:jpeg png jpg',
//            'detail_media' => 'required | mimes:jpeg png jpg'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
        if (!empty($request->has('reference'))) {
//            $check = VehicleStation::where('reference', $request->input('reference'))->first();
            $check = $this->stationRepository->check('reference', ['reference' => $request->input('reference')]);
            if ($check) {
                return response()->json(
                    [
                        'message' => 'Reference is duplicate',
                    ], 401
                );
            } else {
                $station = $this->stationRepository->create($request->all());
                $station->update([$station->reference = $request->input('reference')]);
            }
        } else {
            $station = $this->stationRepository->create($request->all());
            $station->update([$station->reference = rand()]);
        }
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
            $media = $this->mediaRepository->create([
                'name' => $name,
                'path' => '/upload/' . $name,
                'type' => 2
            ]);

            $station->medias()->save($media);
        }

        $media->mediaable()->save($station);
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
                $media = $this->mediaRepository->create([
                    'name' => $name,
                    'path' => '/upload/' . $name,
                    'type' => 2
                ]);
                $station->medias()->save($media);
                $data[$key] = $media;
            }
        }

        return $station;
    }

    public function update($id, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required | numeric | digits:11',
//            'cover_media' => 'required | image | mimes: jpg, png',
//            'detail_media' => 'required | image | mimes: jpg, png'
        ]);
        if ($validate->fails()) {
            return response()->json(
                [
                    'error' => $validate->errors(),
                ], 401
            );
        }
        if (!empty($request->has('phone'))) {
//            $check = VehicleStation::where('phone', $request->input('phone'))->where('id', '<>', $id)->first();
            $check = $this->stationRepository->check('phone', [
                'phone' => $request->input('phone'),
                'id' => $request->input('id')
            ]);
            if ($check) {
                return response()->json(
                    [
                        'message' => 'Reference is duplicate',
                    ], 401
                );
            } else {
                $station = $this->stationRepository->update($id, $request->all());
                $station->update([$station->reference = $request->input('reference')]);
            }
        }

        $station = $this->stationRepository->findId($id);
        if ($file = $request->file('cover_media')) {
            if (count(array($request->cover_media)) > 1) {
                return response()->json(
                    [
                        'message' => 'Cover media only one',
                    ]
                );
            }
            $media = $station->medias()->get();
            $station->medias()->delete();

            if (File::exists(public_path() . '/upload/' . $media)) {
                File::delete(public_path() . '/upload/' . $media);
            }

            $request->cover_media->store(public_path() . '/upload');

            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);

            $media = $this->mediaRepository->create([
                'name' => $name,
                'path' => '/upload/' . $name,
                'type' => 1
            ]);
            $station->medias()->save($media);
        }
        if ($request->file('detail_media')) {
            if (count(array($request->detail_media)) > 5) {
                return response()->json(
                    [
                        'message' => 'Detail media dont more than 5',
                    ]
                );
            }
            $data = [];
            $detailmedia = $station->detail_medias()->get();
            if (File::exists(public_path() . '/upload/' . $detailmedia)) {
                File::delete(public_path() . '/upload/' . $detailmedia);
            }

            foreach ($request->file('detail_media') as $key => $file) {
                $name = rand() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/upload', $name);

                $media = $this->mediaRepository->create([
                    'name' => $name,
                    'path' => '/upload/' . $name,
                    'type' => 2
                ]);
                $station->medias()->save($media);
                $data[$key] = $media;
            }
        }

        return $station;
    }

    public function delete($id)
    {
        return $this->stationRepository->delete($id);
    }

    public function search(array $inputs)
    {
        return $this->stationRepository->search($inputs);
    }
}
