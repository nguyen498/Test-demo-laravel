<?php

namespace App\Services;

use App\Models\Media;

class BaseService
{
    public function checkMedia($input, $count)
    {
        if (count(array($input)) > $count) {
            return ([
                'is_fail' => true,
                'code' => '002',
                'message' => 'Error media',
            ]);
        }
        return ['is_fail' => false];
    }

    public function saveCoverMedia($request)
    {
        $file = $request->file('cover_media');

        $request->cover_media->store(public_path() . '/upload');

        $name = rand() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/upload', $name);

        $media = $this->mediaRepository->create(new Media(), [
            'name' => $name,
            'path' => '/upload/' . $name,
            'type' => 1
        ]);
        return $media;
    }

    public function saveDetailMedia($request, $object, $data)
    {
        foreach ($request->file('detail_media') as $key => $file) {
            $name = rand() . '.' . $file->getClientOriginalName();
            $file->move(public_path() . '/upload', $name);
            $media = $this->mediaRepository->create(new Media(), [
                'name' => $name,
                'path' => '/upload/' . $name,
                'type' => 2
            ]);
            $object->medias()->save($media);
            $data[$key] = $media;
        }
        return $data;
    }
}
