<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'price', 'name', 'kind_id', 'user_id', 'disc', 'ended', 'img_url', 'classes_num'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kind()
    {
        return $this->belongsTo(kind::class);
    }
    public function classses()
    {
        return $this->hasMany(Classs::class);
    }
    public function users()
    {
        return $this->belongsToMany(user::class);
    }
}
