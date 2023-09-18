<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classs extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'course_id', 'name', 'index', 'disc', 'video_url'
    ];
    public function course()
    {
        return $this->belongsTo(course::class);
    }
    public function questions()
    {
        return $this->hasMany(question::class);
    }
}
