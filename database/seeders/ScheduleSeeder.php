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

        // Data mata pelajaran per tingkat
        $subjectsByGrade = [
            '1' => [
                'Pendidikan Agama Islam',
                'Pendidikan Pancasila',
                'Bahasa Indonesia',
                'Matematika',
                'Seni Budaya dan Prakarya',
                'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'Bahasa Inggris',
                'Mengaji'
            ],
            '2' => [
                'Pendidikan Agama Islam',
                'Pendidikan Pancasila',
                'Bahasa Indonesia',
                'Matematika',
                'Seni Budaya dan Prakarya',
                'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'Bahasa Inggris',
                'Mengaji'
            ],
            '3' => [
                'Pendidikan Agama Islam',
                'Pendidikan Pancasila',
                'Bahasa Indonesia',
                'Matematika',
                'Ilmu Pengetahuan Alam',
                'Ilmu Pengetahuan Sosial',
                'Seni Budaya dan Prakarya',
                'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'Bahasa Inggris',
                'Mengaji'
            ],
            '4' => [
                'Pendidikan Agama Islam',
                'Pendidikan Pancasila',
                'Bahasa Indonesia',
                'Matematika',
                'Ilmu Pengetahuan Alam',
                'Ilmu Pengetahuan Sosial',
                'Seni Budaya dan Prakarya',
                'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'Bahasa Inggris',
                'Mengaji'
            ],
            '5' => [
                'Pendidikan Agama Islam',
                'Pendidikan Pancasila',
                'Bahasa Indonesia',
                'Matematika',
                'Ilmu Pengetahuan Alam',
                'Ilmu Pengetahuan Sosial',
                'Seni Budaya dan Prakarya',
                'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'Bahasa Inggris',
                'Mengaji'
            ],
            '6' => [
                'Pendidikan Agama Islam',
                'Pendidikan Pancasila',
                'Bahasa Indonesia',
                'Matematika',
                'Ilmu Pengetahuan Alam',
                'Ilmu Pengetahuan Sosial',
                'Seni Budaya dan Prakarya',
                'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'Bahasa Inggris',
                'Mengaji'
            ],
        ];

        // Hari sekolah
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Jam pelajaran (sesuai kurikulum SD)
        $timeSlots = [
            ['07:30:00', '08:10:00'], // Jam ke-1
            ['08:10:00', '08:50:00'], // Jam ke-2
            ['08:50:00', '09:30:00'], // Jam ke-3
            ['09:30:00', '10:00:00'], // Istirahat 1
            ['10:00:00', '10:40:00'], // Jam ke-4
            ['10:40:00', '11:20:00'], // Jam ke-5
            ['11:20:00', '12:00:00'], // Jam ke-6
        ];

        $schedules = [];
        $scheduleCount = 0;

        foreach ($classrooms as $classroom) {
            $tingkat = $classroom->tingkat;
            $subjects = $subjectsByGrade[$tingkat] ?? [];

            // Map guru khusus
            $specialTeachers = [
                'Mengaji' => $classroom->guru_ngaji_id,
                'Pendidikan Jasmani, Olahraga dan Kesehatan' => $classroom->guru_olahraga_id,
            ];

            // Distribusi mata pelajaran ke hari
            $subjectIndex = 0;
            $dayIndex = 0;

            while ($subjectIndex < count($subjects) && $dayIndex < count($days)) {
                $subject = $subjects[$subjectIndex];

                // Tentukan jumlah jam untuk mata pelajaran
                $hoursNeeded = $this->getHoursForSubject($subject, $tingkat);

                // Distribusikan jam ke time slots
                $timeSlotIndex = 0;
                $hoursAssigned = 0;

                while ($hoursAssigned < $hoursNeeded && $timeSlotIndex < count($timeSlots)) {
                    $day = $days[$dayIndex];
                    $timeSlot = $timeSlots[$timeSlotIndex];

                    // Cari guru untuk mata pelajaran ini
                    $teacherId = $specialTeachers[$subject] ?? $classroom->wali_kelas_id;

                    // Skip jika tidak ada guru
                    if (!$teacherId) {
                        $timeSlotIndex++;
                        continue;
                    }

                    $schedules[] = [
                        'school_year_id' => $activeYear->id,
                        'classroom_id' => $classroom->id,
                        'teacher_id' => $teacherId,
                        'subject' => $subject,
                        'day' => $day,
                        'start_time' => $timeSlot[0],
                        'end_time' => $timeSlot[1],
                    ];

                    $scheduleCount++;
                    $hoursAssigned++;
                    $timeSlotIndex++;

                    // Jika sudah habis time slot di hari ini, pindah ke hari berikutnya
                    if ($timeSlotIndex >= count($timeSlots)) {
                        $dayIndex++;
                        $timeSlotIndex = 0;

                        // Jika sudah habis hari, reset ke hari pertama
                        if ($dayIndex >= count($days)) {
                            $dayIndex = 0;
                        }
                    }
                }

                $subjectIndex++;

                // Reset day index setelah selesai satu mata pelajaran
                $dayIndex = ($dayIndex + 1) % count($days);
            }
        }

        // Insert data ke tabel schedules
        foreach ($schedules as $schedule) {
            DB::table('schedules')->insert([
                ...$schedule,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Seeder schedules berhasil ditambahkan!');
        $this->command->info('Total: ' . $scheduleCount . ' jadwal');
        $this->command->info('Tahun ajaran: ' . $activeYear->name . ' ' . $activeYear->semester);

        // Summary per hari
        $summary = [];
        foreach ($days as $day) {
            $count = count(array_filter($schedules, fn($s) => $s['day'] === $day));
            $summary[] = "$day: $count jadwal";
        }

        $this->command->info('Distribusi hari: ' . implode(', ', $summary));
    }

    /**
     * Tentukan jumlah jam untuk mata pelajaran berdasarkan tingkat
     */
    private function getHoursForSubject(string $subject, string $tingkat): int
    {
        $baseHours = [
            'Pendidikan Agama Islam' => 3,
            'Pendidikan Pancasila' => 2,
            'Bahasa Indonesia' => 6,
            'Matematika' => 5,
            'Ilmu Pengetahuan Alam' => 3,
            'Ilmu Pengetahuan Sosial' => 3,
            'Seni Budaya dan Prakarya' => 4,
            'Pendidikan Jasmani, Olahraga dan Kesehatan' => 3,
            'Bahasa Inggris' => 2,
            'Mengaji' => 2,
        ];

        // Penyesuaian untuk tingkat 1-2
        if (in_array($tingkat, ['1', '2'])) {
            if ($subject === 'Bahasa Indonesia') return 8;
            if ($subject === 'Matematika') return 6;
            if ($subject === 'Pendidikan Pancasila') return 3;
        }

        return $baseHours[$subject] ?? 2;
    }
}
