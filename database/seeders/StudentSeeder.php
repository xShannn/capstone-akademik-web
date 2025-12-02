<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $genders = ['Laki-laki', 'Perempuan'];

        foreach (Classroom::all() as $classroom) {

            foreach (range(1, 20) as $i) {

                $user = User::factory()->create([
                    'name' => "Siswa {$classroom->nama_kelas}-$i",
                    'email' => strtolower("siswa{$classroom->nama_kelas}{$i}@example.com"),
                    'password' => bcrypt('password'),
                    'role' => 'murid',
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'classroom_id' => $classroom->id,
                    'nisn' => rand(1000000000, 9999999999),
                    'nis' => rand(100000, 999999),
                    'nama_lengkap' => "Siswa {$classroom->nama_kelas}-$i",
                    'jenis_kelamin' => $genders[array_rand($genders)],
                    'tahun_masuk' => 2023,
                    'status_aktif' => 'Aktif',
                ]);
            }
        }
    }
}
