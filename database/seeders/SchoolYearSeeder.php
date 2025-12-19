<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolYears = [
            [
                'name' => '2023/2024',
                'semester' => 'Genap',
                'start_date' => '2024-01-08',
                'end_date' => '2024-06-14',
                'is_active' => false,
            ],
            [
                'name' => '2024/2025',
                'semester' => 'Ganjil',
                'start_date' => '2024-07-15',
                'end_date' => '2024-12-20',
                'is_active' => true, // Tahun ajaran aktif
            ],
            [
                'name' => '2024/2025',
                'semester' => 'Genap',
                'start_date' => '2025-01-06',
                'end_date' => '2025-06-13',
                'is_active' => false,
            ],
        ];

        foreach ($schoolYears as $year) {
            DB::table('school_years')->insert([
                ...$year,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Seeder school_years berhasil ditambahkan!');
        $this->command->info('Total: ' . count($schoolYears) . ' tahun ajaran');
        $this->command->info('Tahun ajaran aktif: 2024/2025 Ganjil');
    }
}
