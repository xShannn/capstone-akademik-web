<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Ambil ID semua guru dan putar untuk mengajar di jam berbeda
        $teachers = Teacher::pluck('id');
        $teacherIndex = 0;

        // Cek jika tidak ada guru, batalkan seeder untuk menghindari error
        if ($teachers->isEmpty()) {
            echo "Peringatan: Tidak ada data guru. Seeder dibatalkan.\n";
            return;
        }

        foreach (Classroom::all() as $classroom) {

            // Asumsi: Nama kelas selalu diawali dengan angka (misal: '1A', '3B', '6C')
            $grade = (int) substr($classroom->name, 0, 1);

            // Flags untuk memastikan mata pelajaran khusus hanya 1x seminggu
            $hasSport = false;
            $hasMengaji = false;

            // --- 1. Konfigurasi Jam Berdasarkan Tingkat ---
            // Tingkat 1-3: Jam pulang lebih cepat (misal jam 12:00)
            // Tingkat 4-6: Jam pulang lebih lambat (misal jam 14:00)
            $generalEndHour = ($grade >= 1 && $grade <= 3) ? 12 : 14;


            foreach ($days as $day) {

                $endHour = ($day === 'Jumat') ? 11 : $generalEndHour; // Jumat selalu lebih awal
                $startHour = 7;

                // Tentukan jadwal dan subjek khusus
                $sportDay = 'Selasa';
                $sportHour = 9;

                $mengajiDay = 'Kamis';
                $mengajiHour = 8;

                // Loop untuk jam pelajaran dari jam 7:00 hingga jam selesai
                for ($hour = $startHour; $hour < $endHour; $hour++) {

                    $subject = 'Pelajaran Umum';
                    $teacherId = $teachers[$teacherIndex % count($teachers)];
                    $isSpecialSubject = false; // Flag untuk melacak apakah jam ini sudah diisi mapel khusus

                    // --- 2. Logika Pelajaran Khusus ---

                    // A. Olahraga (Tingkat 1-6, 1x seminggu)
                    if (!$hasSport && $day === $sportDay && $hour === $sportHour) {
                        $subject = 'Olahraga';
                        $hasSport = true;
                        $isSpecialSubject = true;
                    }

                    // B. Mengaji (Tingkat 1-3 SAJA, 1x seminggu)
                    elseif ($grade <= 3 && !$hasMengaji && $day === $mengajiDay && $hour === $mengajiHour) {
                        $subject = 'Mengaji';
                        $hasMengaji = true;
                        $isSpecialSubject = true;
                    }

                    // Jika jam ini sudah diisi mapel khusus, kita lanjutkan loop agar 
                    // Pelajaran Umum tidak menimpa, atau kita pastikan guru yang mengajar 
                    // Olahraga/Mengaji adalah guru yang sesuai (jika ada).

                    // Asumsi: teacher_id untuk Pelajaran Umum diganti-ganti, 
                    // untuk mapel khusus bisa jadi guru yang berbeda, 
                    // tapi di sini kita menggunakan rotasi umum untuk kesederhanaan.

                    if (!$isSpecialSubject) {
                        // Ganti guru untuk Pelajaran Umum
                        $teacherIndex++;
                    }

                    // --- 3. Buat Jadwal ---
                    Schedule::create([
                        'classroom_id' => $classroom->id,
                        // teacher_id akan diisi null jika tidak ada guru, tapi 
                        // karena kita pastikan ada guru di atas, teacherId akan selalu terisi.
                        'teacher_id' => $teacherId,
                        'day' => $day,
                        'subject' => $subject,
                        'start_time' => sprintf("%02d:00", $hour),
                        'end_time' => sprintf("%02d:00", $hour + 1),
                    ]);
                }
            }
        }
    }
}

// namespace Database\Seeders;

// use App\Models\Schedule;
// use App\Models\Classroom;
// use App\Models\Teacher;
// use Illuminate\Database\Seeder;

// class ScheduleSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

//         foreach (Classroom::all() as $classroom) {

//             foreach ($days as $day) {

//                 $startHour = 7;
//                 $endHour = $day === 'Jumat' ? 11 : 14;

//                 for ($hour = $startHour; $hour < $endHour; $hour++) {

//                     // Dapatkan wali kelas
//                     $teacher = Teacher::find($classroom->wali_kelas_id);

//                     Schedule::create([
//                         'classroom_id' => $classroom->id,
//                         'teacher_id' => $teacher->id ?? null,
//                         'day' => $day,
//                         'start_time' => sprintf("%02d:00", $hour),
//                         'end_time' => sprintf("%02d:00", $hour + 1),
//                         'subject' => 'Pelajaran Umum',
//                     ]);
//                 }
//             }
//         }
//     }
// }
