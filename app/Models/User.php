<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    public $timestamps = false;

    protected $fillable = [
        'badget', 'role_id', 'name', 'blocked', 'email', 'password', 'phone_no', 'rating', 'subscribers', 'img_url'
    ];

    public function courses()
    {
        return $this->hasMany(course::class);
    }
    public function objections()
    {
        return $this->hasMany(objection::class);
    }
    public function notifications()
    {
        return $this->hasMany(notification::class);
    }
    public function course()
    {
        return $this->belongsToMany(course::class);
    }
}
