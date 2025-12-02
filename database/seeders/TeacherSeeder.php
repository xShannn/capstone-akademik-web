<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $genders = ['Laki-laki', 'Perempuan'];

        // Buat 18 Wali Kelas (1A–6C)
        foreach (range(1, 18) as $i) {
            $user = User::factory()->create([
                'name' => "Guru Wali $i",
                'email' => "wali$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'guru',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => '1980' . rand(1000, 9999),
                'nama_lengkap' => "Guru Wali Kelas $i",
                'jenis_kelamin' => $genders[array_rand($genders)],
                'jabatan' => 'Wali Kelas',
                'email' => $user->email,
                'nomor_telepon' => '0812' . rand(1000000, 9999999),
                'tanggal_masuk' => now()->subYears(rand(1, 10))
            ]);
        }

        // Guru Mengaji (3 orang)
        foreach (range(1, 3) as $i) {
            $user = User::factory()->create([
                'name' => "Guru Mengaji $i",
                'email' => "mengaji$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'guru',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => '1990' . rand(1000, 9999),
                'nama_lengkap' => "Guru Mengaji $i",
                'jenis_kelamin' => $genders[array_rand($genders)],
                'jabatan' => 'Guru Mengaji',
                'email' => $user->email,
            ]);
        }

        // Guru Olahraga (3 orang)
        foreach (range(1, 3) as $i) {
            $user = User::factory()->create([
                'name' => "Guru Olahraga $i",
                'email' => "olahraga$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'guru',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => '2000' . rand(1000, 9999),
                'nama_lengkap' => "Guru Olahraga $i",
                'jenis_kelamin' => $genders[array_rand($genders)],
                'jabatan' => 'Guru Olahraga',
                'email' => $user->email,
            ]);
        }
    }
}
