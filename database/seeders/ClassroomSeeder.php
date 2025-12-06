<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data guru dari database
        $teachers = DB::table('teachers')->get();

        // Mapping guru berdasarkan jabatan
        $waliKelas = [];
        $guruNgaji = [];
        $guruOlahraga = [];

        foreach ($teachers as $teacher) {
            $jabatan = $teacher->jabatan;

            if (str_contains($jabatan, 'Wali Kelas')) {
                // Ekstrak kelas dari jabatan (contoh: "Wali Kelas 1A" -> "1A")
                preg_match('/Wali Kelas (\d+[A-C])/', $jabatan, $matches);
                if (isset($matches[1])) {
                    $waliKelas[$matches[1]] = $teacher->id;
                }
            } elseif (str_contains($jabatan, 'Guru Mengaji')) {
                // Ekstrak kelas dari jabatan (contoh: "Guru Mengaji (Kelas 1)" -> "1")
                preg_match('/Kelas (\d+)/', $jabatan, $matches);
                if (isset($matches[1])) {
                    $guruNgaji[$matches[1]] = $teacher->id;
                }
            } elseif (str_contains($jabatan, 'Guru Olahraga')) {
                // Ekstrak kelas dari jabatan (contoh: "Guru Olahraga (Kelas 1)" -> "1")
                preg_match('/Kelas (\d+)/', $jabatan, $matches);
                if (isset($matches[1])) {
                    $guruOlahraga[$matches[1]] = $teacher->id;
                }
            }
        }

        // Data kelas
        $classrooms = [];
        $tingkats = ['1', '2', '3', '4', '5', '6'];
        $kelasHuruf = ['A', 'B', 'C'];

        foreach ($tingkats as $tingkat) {
            foreach ($kelasHuruf as $huruf) {
                $namaKelas = $tingkat . $huruf;
                $tingkatAngka = (int)$tingkat;

                // Cari guru berdasarkan mapping
                $waliKelasId = $waliKelas[$namaKelas] ?? null;

                // Untuk guru ngaji: kelas 1-3 ada guru khusus, 4-6 pakai guru ngaji kelas 3
                if ($tingkatAngka <= 3) {
                    $guruNgajiId = $guruNgaji[$tingkat] ?? null;
                } else {
                    $guruNgajiId = $guruNgaji['3'] ?? null; // Kelas 4-6 pakai guru ngaji kelas 3
                }

                // Cari guru olahraga berdasarkan tingkat
                $guruOlahragaId = $guruOlahraga[$tingkat] ?? null;

                $classrooms[] = [
                    'nama_kelas' => $namaKelas,
                    'tingkat' => $tingkat,
                    'wali_kelas_id' => $waliKelasId,
                    'guru_ngaji_id' => $guruNgajiId,
                    'guru_olahraga_id' => $guruOlahragaId,
                ];
            }
        }

        // Insert data ke tabel classrooms
        foreach ($classrooms as $classroom) {
            DB::table('classrooms')->insert([
                ...$classroom,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Seeder classrooms berhasil ditambahkan!');
        $this->command->info('Total: ' . count($classrooms) . ' kelas');
        $this->command->info('Distribusi: 6 tingkat × 3 kelas = 18 kelas');

        // Tampilkan summary
        $kelasDenganWali = count(array_filter($classrooms, fn($c) => !is_null($c['wali_kelas_id'])));
        $kelasDenganNgaji = count(array_filter($classrooms, fn($c) => !is_null($c['guru_ngaji_id'])));
        $kelasDenganOlahraga = count(array_filter($classrooms, fn($c) => !is_null($c['guru_olahraga_id'])));

        $this->command->info("Kelas dengan wali kelas: {$kelasDenganWali}/18");
        $this->command->info("Kelas dengan guru ngaji: {$kelasDenganNgaji}/18");
        $this->command->info("Kelas dengan guru olahraga: {$kelasDenganOlahraga}/18");
    }
}
