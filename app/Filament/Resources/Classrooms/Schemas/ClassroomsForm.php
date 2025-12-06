<?php

namespace App\Filament\Resources\Classrooms\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class ClassroomsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kelas')
                    ->schema([
                        TextInput::make('nama_kelas')
                            ->label('Nama Kelas')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: 1A, 2B, 6C')
                            ->helperText('Format: Tingkat + Huruf (contoh: 1A)'),

                        Select::make('tingkat')
                            ->label('Tingkat')
                            ->required()
                            ->options([
                                1 => 'Kelas 1',
                                2 => 'Kelas 2',
                                3 => 'Kelas 3',
                                4 => 'Kelas 4',
                                5 => 'Kelas 5',
                                6 => 'Kelas 6',
                            ])
                            ->native(false)
                            ->placeholder('Pilih tingkat kelas'),
                    ])
                    ->columns(2),

                Section::make('Guru Pengajar')
                    ->description('Tentukan guru untuk masing-masing peran')
                    ->schema([
                        Select::make('wali_kelas_id')
                            ->label('Wali Kelas')
                            ->relationship('waliKelas', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih wali kelas')
                            ->helperText('Guru yang bertanggung jawab sebagai wali kelas'),

                        Select::make('guru_ngaji_id')
                            ->label('Guru Ngaji')
                            ->relationship('guruNgaji', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih guru ngaji')
                            ->helperText('Guru yang mengajar pelajaran ngaji'),

                        Select::make('guru_olahraga_id')
                            ->label('Guru Olahraga')
                            ->relationship('guruOlahraga', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih guru olahraga')
                            ->helperText('Guru yang mengajar pelajaran olahraga'),
                    ])
                    ->columns(3),
            ]);
    }
}
