<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil tahun ajaran aktif
        $activeYear = DB::table('school_years')->where('is_active', true)->first();

        if (!$activeYear) {
            $this->command->error('Tidak ada tahun ajaran aktif!');
            return;
        }

        // Ambil semua kelas
        $classrooms = DB::table('classrooms')->get();

        // Data mata pelajaran per tingkat dengan distribusi jam
        // PERBAIKAN: Hapus 'Mengaji' dari kelas 4-6
        $subjectsByGrade = [
            '1' => [
                ['subject' => 'Pendidikan Agama Islam', 'hours' => 3],
                ['subject' => 'Pendidikan Pancasila', 'hours' => 3],
                ['subject' => 'Bahasa Indonesia', 'hours' => 8],
                ['subject' => 'Matematika', 'hours' => 6],
                ['subject' => 'Seni Budaya dan Prakarya', 'hours' => 4],
                ['subject' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'hours' => 3, 'special' => 'olahraga'],
                ['subject' => 'Bahasa Inggris', 'hours' => 2],
                ['subject' => 'Mengaji', 'hours' => 2, 'special' => 'ngaji'],
            ],
            '2' => [
                ['subject' => 'Pendidikan Agama Islam', 'hours' => 3],
                ['subject' => 'Pendidikan Pancasila', 'hours' => 3],
                ['subject' => 'Bahasa Indonesia', 'hours' => 8],
                ['subject' => 'Matematika', 'hours' => 6],
                ['subject' => 'Seni Budaya dan Prakarya', 'hours' => 4],
                ['subject' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'hours' => 3, 'special' => 'olahraga'],
                ['subject' => 'Bahasa Inggris', 'hours' => 2],
                ['subject' => 'Mengaji', 'hours' => 2, 'special' => 'ngaji'],
            ],
            '3' => [
                ['subject' => 'Pendidikan Agama Islam', 'hours' => 3],
                ['subject' => 'Pendidikan Pancasila', 'hours' => 2],
                ['subject' => 'Bahasa Indonesia', 'hours' => 6],
                ['subject' => 'Matematika', 'hours' => 5],
                ['subject' => 'Ilmu Pengetahuan Alam', 'hours' => 3],
                ['subject' => 'Ilmu Pengetahuan Sosial', 'hours' => 3],
                ['subject' => 'Seni Budaya dan Prakarya', 'hours' => 4],
                ['subject' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'hours' => 3, 'special' => 'olahraga'],
                ['subject' => 'Bahasa Inggris', 'hours' => 2],
                ['subject' => 'Mengaji', 'hours' => 2, 'special' => 'ngaji'],
            ],
            // KELAS 4-6: TIDAK ADA MENGAJI
            '4' => [
                ['subject' => 'Pendidikan Agama Islam', 'hours' => 3],
                ['subject' => 'Pendidikan Pancasila', 'hours' => 2],
                ['subject' => 'Bahasa Indonesia', 'hours' => 6],
                ['subject' => 'Matematika', 'hours' => 5],
                ['subject' => 'Ilmu Pengetahuan Alam', 'hours' => 3],
                ['subject' => 'Ilmu Pengetahuan Sosial', 'hours' => 3],
                ['subject' => 'Seni Budaya dan Prakarya', 'hours' => 4],
                ['subject' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'hours' => 3, 'special' => 'olahraga'],
                ['subject' => 'Bahasa Inggris', 'hours' => 2],
                // TIDAK ADA 'Mengaji'
            ],
            '5' => [
                ['subject' => 'Pendidikan Agama Islam', 'hours' => 3],
                ['subject' => 'Pendidikan Pancasila', 'hours' => 2],
                ['subject' => 'Bahasa Indonesia', 'hours' => 6],
                ['subject' => 'Matematika', 'hours' => 5],
                ['subject' => 'Ilmu Pengetahuan Alam', 'hours' => 3],
                ['subject' => 'Ilmu Pengetahuan Sosial', 'hours' => 3],
                ['subject' => 'Seni Budaya dan Prakarya', 'hours' => 4],
                ['subject' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'hours' => 3, 'special' => 'olahraga'],
                ['subject' => 'Bahasa Inggris', 'hours' => 2],
                // TIDAK ADA 'Mengaji'
            ],
            '6' => [
                ['subject' => 'Pendidikan Agama Islam', 'hours' => 3],
                ['subject' => 'Pendidikan Pancasila', 'hours' => 2],
                ['subject' => 'Bahasa Indonesia', 'hours' => 6],
                ['subject' => 'Matematika', 'hours' => 5],
                ['subject' => 'Ilmu Pengetahuan Alam', 'hours' => 3],
                ['subject' => 'Ilmu Pengetahuan Sosial', 'hours' => 3],
                ['subject' => 'Seni Budaya dan Prakarya', 'hours' => 4],
                ['subject' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'hours' => 3, 'special' => 'olahraga'],
                ['subject' => 'Bahasa Inggris', 'hours' => 2],
                // TIDAK ADA 'Mengaji'
            ],
        ];

        // Hari sekolah
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Jam pelajaran (40 menit per jam)
        $timeSlots = [
            ['07:30:00', '08:10:00'], // Jam ke-1
            ['08:10:00', '08:50:00'], // Jam ke-2
            ['08:50:00', '09:30:00'], // Jam ke-3
            ['09:30:00', '10:00:00'], // Istirahat 1 (30 menit)
            ['10:00:00', '10:40:00'], // Jam ke-4
            ['10:40:00', '11:20:00'], // Jam ke-5
            ['11:20:00', '12:00:00'], // Jam ke-6
            ['12:00:00', '12:40:00'], // Jam ke-7 (untuk kelas 4-6)
            ['12:40:00', '13:20:00'], // Jam ke-8 (untuk kelas 4-6)
        ];

        // Hapus jadwal lama untuk tahun ajaran ini
        DB::table('schedules')->where('school_year_id', $activeYear->id)->delete();

        $totalSchedules = 0;

        foreach ($classrooms as $classroom) {
            $tingkat = $classroom->tingkat;
            $gradeSubjects = $subjectsByGrade[$tingkat] ?? [];

            // Validasi: Kelas harus punya wali kelas
            if (!$classroom->wali_kelas_id) {
                $this->command->warn("Kelas {$classroom->nama_kelas} tidak memiliki wali kelas, skip...");
                continue;
            }

            $schedules = [];
            $currentDayIndex = 0;
            $currentTimeSlotIndex = 0;

            // Total jam per minggu untuk kelas ini
            $totalHours = array_sum(array_column($gradeSubjects, 'hours'));

            $this->command->info("Membuat jadwal kelas {$classroom->nama_kelas} ({$totalHours} jam/minggu)");

            foreach ($gradeSubjects as $subjectData) {
                $subject = $subjectData['subject'];
                $hoursNeeded = $subjectData['hours'];
                $specialType = $subjectData['special'] ?? null;

                // Tentukan guru
                if ($specialType === 'olahraga') {
                    $teacherId = $classroom->guru_olahraga_id;
                } elseif ($specialType === 'ngaji') {
                    $teacherId = $classroom->guru_ngaji_id;
                } else {
                    $teacherId = $classroom->wali_kelas_id; // Mapel reguler
                }

                // Skip jika tidak ada guru (khusus untuk mengaji kelas 4-6)
                if (!$teacherId) {
                    if ($specialType === 'ngaji') {
                        $this->command->info("  Mapel Mengaji diabaikan (tidak ada untuk kelas {$tingkat})");
                    }
                    continue;
                }

                // Distribusikan jam untuk mapel ini
                for ($hour = 0; $hour < $hoursNeeded; $hour++) {
                    // Jika time slot habis, pindah ke hari berikutnya
                    if ($currentTimeSlotIndex >= count($timeSlots)) {
                        $currentTimeSlotIndex = 0;
                        $currentDayIndex = ($currentDayIndex + 1) % count($days);
                    }

                    $day = $days[$currentDayIndex];
                    $timeSlot = $timeSlots[$currentTimeSlotIndex];

                    $schedules[] = [
                        'school_year_id' => $activeYear->id,
                        'classroom_id' => $classroom->id,
                        'teacher_id' => $teacherId,
                        'subject' => $subject,
                        'day' => $day,
                        'start_time' => $timeSlot[0],
                        'end_time' => $timeSlot[1],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];

                    $currentTimeSlotIndex++;
                }
            }

            // Insert jadwal untuk kelas ini
            if (!empty($schedules)) {
                DB::table('schedules')->insert($schedules);
                $totalSchedules += count($schedules);
                $this->command->info("  Selesai: " . count($schedules) . " jadwal");
            }
        }

        $this->command->info('========================================');
        $this->command->info('Seeder schedules berhasil ditambahkan!');
        $this->command->info('Total: ' . $totalSchedules . ' jadwal');
        $this->command->info('Tahun ajaran: ' . $activeYear->name . ' ' . $activeYear->semester);

        // Summary per hari
        $this->command->info('Distribusi per hari:');
        foreach ($days as $day) {
            $count = DB::table('schedules')
                ->where('school_year_id', $activeYear->id)
                ->where('day', $day)
                ->count();
            $this->command->info("  {$day}: {$count} jadwal");
        }

        // Summary per mapel khusus
        $this->command->info('Distribusi mapel khusus:');
        $olahragaCount = DB::table('schedules')
            ->where('school_year_id', $activeYear->id)
            ->where('subject', 'Pendidikan Jasmani, Olahraga dan Kesehatan')
            ->count();
        $this->command->info("  Olahraga: {$olahragaCount} jadwal");

        $ngajiCount = DB::table('schedules')
            ->where('school_year_id', $activeYear->id)
            ->where('subject', 'Mengaji')
            ->count();
        $this->command->info("  Mengaji: {$ngajiCount} jadwal (hanya kelas 1-3)");

        // Summary per guru type
        $this->command->info('Jadwal berdasarkan jenis guru:');
        $waliKelasCount = DB::table('schedules')
            ->join('teachers', 'schedules.teacher_id', '=', 'teachers.id')
            ->where('schedules.school_year_id', $activeYear->id)
            ->where('teachers.jabatan', 'Wali Kelas')
            ->count();
        $this->command->info("  Wali Kelas: {$waliKelasCount} jadwal");

        $guruOlahragaCount = DB::table('schedules')
            ->join('teachers', 'schedules.teacher_id', '=', 'teachers.id')
            ->where('schedules.school_year_id', $activeYear->id)
            ->where('teachers.jabatan', 'Guru Olahraga')
            ->count();
        $this->command->info("  Guru Olahraga: {$guruOlahragaCount} jadwal");

        $guruNgajiCount = DB::table('schedules')
            ->join('teachers', 'schedules.teacher_id', '=', 'teachers.id')
            ->where('schedules.school_year_id', $activeYear->id)
            ->where('teachers.jabatan', 'Guru Mengaji')
            ->count();
        $this->command->info("  Guru Mengaji: {$guruNgajiCount} jadwal");
    }
}
