<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_course extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'course_id', 'level', 'rating', 'comment'
    ];
    public function classses()
    {
        return $this->hasMany(comment::class);
    }
}
