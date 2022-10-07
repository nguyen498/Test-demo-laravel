<?php

namespace App\Repositories;

use App\Models\Media;

class MediaRepository
{
    public function create (array $input){
        $media = Media::create($input);
        return $media;
    }

    public function delete ($id){
        $media = Media::find($id);
        return $media->delete();
    }
}
