<?php

namespace App\Models;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'wali_kelas_id',
        'guru_ngaji_id',
        'guru_olahraga_id',
    ];

    public function waliKelas()
    {
        return $this->belongsTo(Teacher::class, 'wali_kelas_id');
    }

    /**
     * Relasi ke guru ngaji
     */
    public function guruNgaji()
    {
        return $this->belongsTo(Teacher::class, 'guru_ngaji_id');
    }

    /**
     * Relasi ke guru olahraga
     */
    public function guruOlahraga()
    {
        return $this->belongsTo(Teacher::class, 'guru_olahraga_id');
    }

    /**
     * Relasi ke siswa di kelas ini
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Relasi jadwal kelas
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
