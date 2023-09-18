<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_course_id', 'comment'
    ];
    public function course()
    {
        return $this->belongsTo(user_course::class);
    }
}
