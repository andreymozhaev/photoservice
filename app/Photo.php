<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Photo extends Model
{
    public function getUrlAttribute()
    {
        return URL::to('/').Storage::url($this->attributes['url']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
