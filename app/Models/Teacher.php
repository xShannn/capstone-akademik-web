<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'nik',
        'nomor_telepon',
        'email',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'dusun',
        'kode_pos',
        'jabatan',
        'status',
        'tanggal_masuk',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke classroom (jika guru adalah wali kelas)
    public function waliKelas()
    {
        return $this->hasOne(Classroom::class, 'wali_kelas_id');
    }

    // Relasi ke classroom (jika guru adalah guru mengaji)
    public function guruMengaji()
    {
        return $this->hasOne(Classroom::class, 'guru_ngaji_id');
    }

    // Relasi ke classroom (jika guru adalah guru olahraga)
    public function guruOlahraga()
    {
        return $this->hasOne(Classroom::class, 'guru_olahraga_id');
    }

    public function userAccount()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
