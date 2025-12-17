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
        $this->command->info('Memulai ClassroomSeeder...');

        // 1. AMBIL SEMUA GURU
        $allTeachers = DB::table('teachers')->get();

        // 2. KELOMPOKKAN GURU BERDASARKAN JABATAN
        $waliKelasGurus = $allTeachers->where('jabatan', 'Wali Kelas')->values();
        $olahragaGurus = $allTeachers->where('jabatan', 'Guru Olahraga')->values();
        $mengajiGurus = $allTeachers->where('jabatan', 'Guru Mengaji')->values();

        $this->command->info("Jumlah Wali Kelas: " . count($waliKelasGurus));
        $this->command->info("Jumlah Guru Olahraga: " . count($olahragaGurus));
        $this->command->info("Jumlah Guru Mengaji: " . count($mengajiGurus));

        // VALIDASI: Cukupkah guru?
        if (count($waliKelasGurus) < 18) {
            $this->command->error("ERROR: Butuh 18 Wali Kelas, hanya tersedia " . count($waliKelasGurus));
            $this->command->info("Silakan tambah guru di TeacherSeeder!");
            return;
        }

        if (count($olahragaGurus) < 6) {
            $this->command->error("ERROR: Butuh 6 Guru Olahraga, hanya tersedia " . count($olahragaGurus));
            $this->command->info("Silakan tambah guru di TeacherSeeder!");
            return;
        }

        if (count($mengajiGurus) < 3) {
            $this->command->error("ERROR: Butuh 3 Guru Mengaji, hanya tersedia " . count($mengajiGurus));
            $this->command->info("Silakan tambah guru di TeacherSeeder!");
            return;
        }

        // 3. HAPUS DATA LAMA (jika ada) PAKAI DELETE(), BUKAN TRUNCATE()
        // Cek dulu apakah tabel sudah ada data
        $existingCount = DB::table('classrooms')->count();
        if ($existingCount > 0) {
            $this->command->info("Menghapus {$existingCount} data kelas lama...");

            // OPTION A: Delete semua (aman untuk foreign key)
            DB::table('classrooms')->delete();

            // OPTION B: Reset auto increment
            // DB::statement('ALTER TABLE classrooms AUTO_INCREMENT = 1');
        }

        // 4. BUAT DATA KELAS BARU
        $classrooms = [];
        $tingkats = ['1', '2', '3', '4', '5', '6'];
        $kelasHuruf = ['A', 'B', 'C'];

        $waliIndex = 0;

        foreach ($tingkats as $tingkat) {
            foreach ($kelasHuruf as $huruf) {
                $namaKelas = $tingkat . $huruf;
                $tingkatAngka = (int)$tingkat;

                // === ASSIGN WALI KELAS (1 guru per kelas) ===
                $waliKelasId = $waliKelasGurus[$waliIndex]->id ?? null;
                $waliIndex++;

                // === ASSIGN GURU OLAHRAGA (1 guru per tingkat) ===
                $olahragaIndex = ($tingkatAngka - 1) % 6; // 0-5
                $guruOlahragaId = $olahragaGurus[$olahragaIndex]->id ?? null;

                // === ASSIGN GURU MENGAJI (HANYA KELAS 1-3) ===
                $guruNgajiId = null;
                if ($tingkatAngka <= 3) {
                    $ngajiIndex = ($tingkatAngka - 1) % 3; // 0-2
                    $guruNgajiId = $mengajiGurus[$ngajiIndex]->id ?? null;
                }

                // Cek apakah kelas ini sudah ada
                $existingClassroom = DB::table('classrooms')
                    ->where('nama_kelas', $namaKelas)
                    ->first();

                if ($existingClassroom) {
                    // Update existing
                    DB::table('classrooms')
                        ->where('id', $existingClassroom->id)
                        ->update([
                            'wali_kelas_id' => $waliKelasId,
                            'guru_ngaji_id' => $guruNgajiId,
                            'guru_olahraga_id' => $guruOlahragaId,
                            'updated_at' => Carbon::now(),
                        ]);
                } else {
                    // Insert baru
                    $classrooms[] = [
                        'nama_kelas' => $namaKelas,
                        'tingkat' => $tingkat,
                        'wali_kelas_id' => $waliKelasId,
                        'guru_ngaji_id' => $guruNgajiId,
                        'guru_olahraga_id' => $guruOlahragaId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }

                $this->command->info("Kelas {$namaKelas}: Wali={$waliKelasId}, Ngaji={$guruNgajiId}, Olahraga={$guruOlahragaId}");
            }
        }

        // Insert data baru (jika ada)
        if (!empty($classrooms)) {
            DB::table('classrooms')->insert($classrooms);
        }

        $this->command->info('Seeder classrooms berhasil ditambahkan!');

        // 5. SUMMARY
        $totalKelas = DB::table('classrooms')->count();
        $kelasDenganWali = DB::table('classrooms')->whereNotNull('wali_kelas_id')->count();
        $kelasDenganNgaji = DB::table('classrooms')->whereNotNull('guru_ngaji_id')->count();
        $kelasDenganOlahraga = DB::table('classrooms')->whereNotNull('guru_olahraga_id')->count();

        $this->command->info("Total kelas: {$totalKelas}");
        $this->command->info("Kelas dengan wali kelas: {$kelasDenganWali}/{$totalKelas}");
        $this->command->info("Kelas dengan guru ngaji: {$kelasDenganNgaji}/{$totalKelas} (hanya kelas 1-3)");
        $this->command->info("Kelas dengan guru olahraga: {$kelasDenganOlahraga}/{$totalKelas}");
    }
}
