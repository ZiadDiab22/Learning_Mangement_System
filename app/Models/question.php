<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'class_id', 'ch1', 'ch2', 'ch3', 'ch4', 'correct_answer', 'question'
    ];
    public function classs()
    {
        return $this->belongsTo(classs::class);
    }
}
