<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded=[];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password']=Hash::make($value);
    }

    public function generateToken()
    {
        $this->api_token=Hash::make(Str::random(32));
        $this->save();
        return $this->api_token;
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class);
    }
}
