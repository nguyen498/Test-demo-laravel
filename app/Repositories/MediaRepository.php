<?php

namespace App\Repositories;

use App\Models\Media;

class MediaRepository
{
    public function create ($input){
        $media = Media::create([
            'path' => $input,
            'type' => 1
        ]);
        return $media;
    }

    public function delete ($id){
        $media = Media::find($id);
        return $media->delete();
    }
}
