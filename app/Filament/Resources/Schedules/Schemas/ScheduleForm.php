<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\SchoolYear;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('classroom_id')
                    ->label('Kelas')
                    ->options(Classroom::pluck('nama_kelas', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('teacher_id')
                    ->label('Guru Pengajar')
                    ->options(Teacher::pluck('nama_lengkap', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('school_year_id')
                    ->label('Tahun Ajaran')
                    ->relationship('schoolYear', 'name')
                    ->default(
                        SchoolYear::where('is_active', true)->first()?->id
                            ?? SchoolYear::latest()->first()?->id
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih tahun ajaran aktif'),

                Select::make('day')
                    ->label('Hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                    ])
                    ->required(),

                TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->seconds(false) // Tidak tampilkan detik
                    ->displayFormat('H:i') // Format tampilan: 07:30 (24 jam)
                    ->required(),

                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->seconds(false) // Tidak tampilkan detik
                    ->displayFormat('H:i') // Format tampilan: 07:30 (24 jam)
                    ->required(),

                TextInput::make('subject')
                    ->label('Mata Pelajaran')
                    ->placeholder('Masukkan Nama Mata Pelajaran'),

            ]);
    }
}
