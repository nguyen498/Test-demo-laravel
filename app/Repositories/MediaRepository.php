<?php

namespace App\Repositories;

use App\Models\Media;

class MediaRepository
{
    public function create ($input){
        $media = Media::create($input);
        return $media;
    }
}
