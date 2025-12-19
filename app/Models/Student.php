<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasApiTokens;
    protected $fillable = [
        'nisn',
        'nis',
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
        'tahun_masuk',
        'status_aktif',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'nomor_telepon_ortu',
    ];

    protected $hidden = [
        'password'
    ];

    public function sppPayments()
    {
        return $this->hasMany(SppPayment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Relationship untuk orang tua (parent)
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    // Helper untuk mendapatkan nama kelas lengkap
    public function getNamaKelasLengkapAttribute(): ?string
    {
        if ($this->classroom) {
            return "Kelas {$this->classroom->tingkat} - {$this->classroom->nama_kelas}";
        }
        return null;
    }
}
