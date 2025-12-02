<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'teachers';

    protected $fillable = [
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
        'mapel',
        'status',
        'tanggal_masuk',
        'password',
        'class_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function class()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
