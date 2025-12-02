<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'teacher_id',
        'class_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(Classroom::class); // jika nama model Classes
    }

}
