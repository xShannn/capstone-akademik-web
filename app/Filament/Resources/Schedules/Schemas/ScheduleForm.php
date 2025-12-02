<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Models\Teacher;
use App\Models\Classroom;
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
                    ->required(),

                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required(),

                TextInput::make('subject')
                    ->label('Mata Pelajaran (Opsional)')
                    ->placeholder('Kosongkan bila dia wali kelas'),

            ]);
    }
}
