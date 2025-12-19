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

    // Scope untuk filter berdasarkan tingkat
    public function scopeByTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    // Accessor untuk nama lengkap kelas
    public function getNamaLengkapAttribute(): string
    {
        return "Kelas {$this->tingkat} - {$this->nama_kelas}";
    }

    // Scope untuk ordering
    public function scopeOrderByTingkat($query)
    {
        return $query->orderBy('tingkat')->orderBy('nama_kelas');
    }
    /**
     * Relasi jadwal kelas
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Method untuk mendapatkan semua guru terkait kelas
    public function getAllTeachersAttribute()
    {
        $teachers = collect();

        if ($this->waliKelas) {
            $teachers->push(['role' => 'Wali Kelas', 'teacher' => $this->waliKelas]);
        }

        if ($this->guruNgaji) {
            $teachers->push(['role' => 'Guru Ngaji', 'teacher' => $this->guruNgaji]);
        }

        if ($this->guruOlahraga) {
            $teachers->push(['role' => 'Guru Olahraga', 'teacher' => $this->guruOlahraga]);
        }

        return $teachers;
    }
    // Untuk Filament: Label untuk select options
    public function getLabelAttribute(): string
    {
        return $this->nama_kelas_lengkap;
    }
}
