<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $wali = Teacher::where('jabatan', 'Wali Kelas')->pluck('id')->toArray();
        $mengaji = Teacher::where('jabatan', 'Guru Mengaji')->pluck('id')->toArray();
        $olahraga = Teacher::where('jabatan', 'Guru Olahraga')->pluck('id')->toArray();

        $waliIndex = 0;

        foreach (range(1, 6) as $tingkat) {
            foreach (['A','B','C'] as $kelasHuruf) {

                Classroom::create([
                    'nama_kelas' => $tingkat . $kelasHuruf,
                    'tingkat' => $tingkat,
                    'wali_kelas_id' => $wali[$waliIndex] ?? null,
                    'guru_ngaji_id' => $tingkat <= 3 ? ($mengaji[array_rand($mengaji)] ?? null) : null,
                    'guru_olahraga_id' => $olahraga[array_rand($olahraga)] ?? null,
                ]);

                $waliIndex++;
            }
        }
    }
}
