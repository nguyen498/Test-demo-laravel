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

    protected $casts = [
        'name' => 'string',
        'path' => 'string',
        'mediaable_id' => 'integer'
    ];

    //
    public function mediaable()
    {
        return $this->morphTo();
    }
}
