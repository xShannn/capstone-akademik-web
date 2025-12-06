<?php

namespace App\Models;

use App\Enums\SppStatus;
use Illuminate\Database\Eloquent\Model;

class SppPayment extends Model
{
    protected $fillable = [
        'student_id',
        'month',
        'year',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => 'string',
    ];


    /**
     * Relasi ke Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Accessor untuk nama bulan (opsional)
     */
    public function getMonthNameAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->month, 10));
    }
}
