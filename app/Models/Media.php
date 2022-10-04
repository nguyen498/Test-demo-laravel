<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'name',
        'path',
        'mediaable_id'
    ];

    //
    public function mediaable()
    {
        return $this->morphTo();
    }
}
