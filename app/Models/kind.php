<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kind extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name', 'subject_id'
    ];

    public function subject()
    {
        return $this->belongsTo(subject::class);
    }
    public function courses()
    {
        return $this->hasMany(course::class);
    }
}
