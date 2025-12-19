<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Hitung total data dari kedua model
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();

        return [
            // 1. Stat untuk Total Murid
            Stat::make('Total Murid', $totalStudents)
                ->description('Jumlah seluruh siswa yang terdaftar')
                ->color('success'), // Warna hijau

            // 2. Stat untuk Total Guru
            Stat::make('Total Guru', $totalTeachers)
                ->description('Jumlah seluruh guru yang terdaftar')
                ->color('info'), // Warna biru

            // 3. (Opsional) Stat Tambahan, misalnya total Kelas
            // Stat::make('Total Kelas', \App\Models\Classroom::count())
            //     ->color('warning'),
        ];
    }
}
