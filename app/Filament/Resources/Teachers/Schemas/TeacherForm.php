<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Form;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Wizard\Step;


class TeacherForm
{
    public static function schema(): array
    {
        return [
            Wizard::make([

                /*
                |--------------------------------------------------------------------------
                | STEP 1 — IDENTITAS GURU
                |--------------------------------------------------------------------------
                */
                Step::make('Identitas Guru')
                    ->schema([
                        TextInput::make('nip')->label('NIP')->required(),
                        TextInput::make('nama_lengkap')->required(),

                        Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),

                        TextInput::make('tempat_lahir'),
                        DatePicker::make('tanggal_lahir'),

                        Select::make('agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Konghucu' => 'Konghucu',
                            ])
                            ->searchable(),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->required(),
                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | STEP 2 — KONTAK & ALAMAT
                |--------------------------------------------------------------------------
                */
                Step::make('Kontak & Alamat')
                    ->schema([
                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->label('Email'),

                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),

                        Select::make('provinsi')
                            ->label('Provinsi')
                            ->options([
                                'Lampung' => 'Lampung',
                                'Sumatera Selatan' => 'Sumatera Selatan',
                                'Bengkulu' => 'Bengkulu',
                                'Sumatera Barat' => 'Sumatera Barat',
                                'Jambi' => 'Jambi',
                            ])
                            ->searchable()
                            ->required(),

                        Select::make('kabupaten')
                            ->label('Kabupaten / Kota')
                            ->options([
                                // Lampung
                                'Bandar Lampung' => 'Bandar Lampung',
                                'Metro' => 'Metro',
                                'Lampung Selatan' => 'Lampung Selatan',
                                'Lampung Tengah' => 'Lampung Tengah',
                                'Lampung Timur' => 'Lampung Timur',
                                'Lampung Barat' => 'Lampung Barat',
                                'Lampung Utara' => 'Lampung Utara',
                                'Pesawaran' => 'Pesawaran',
                                'Pringsewu' => 'Pringsewu',
                                'Tanggamus' => 'Tanggamus',
                                'Pesisir Barat' => 'Pesisir Barat',
                                'Way Kanan' => 'Way Kanan',
                                'Mesuji' => 'Mesuji',
                                'Tulang Bawang' => 'Tulang Bawang',
                                'Tulang Bawang Barat' => 'Tulang Bawang Barat',

                                // Sekitar Lampung
                                'Ogan Komering Ulu' => 'Ogan Komering Ulu',
                                'Ogan Komering Ilir' => 'Ogan Komering Ilir',
                                'Banyuasin' => 'Banyuasin',
                                'Rejang Lebong' => 'Rejang Lebong',
                                'Mukomuko' => 'Mukomuko',
                            ])
                            ->searchable()
                            ->required(),

                        // input manual
                        TextInput::make('kecamatan')->label('Kecamatan'),
                        TextInput::make('kelurahan')->label('Kelurahan / Desa'),
                        TextInput::make('dusun')->label('Dusun (Opsional)'),
                        TextInput::make('kode_pos')->label('Kode Pos'),
                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | STEP 3 — KEPEGAWAIAN
                |--------------------------------------------------------------------------
                */
                Step::make('Data Kepegawaian')
                    ->schema([

                        Select::make('jabatan')
                            ->options([
                                'Wali Kelas' => 'Wali Kelas',
                                'Guru Olahraga' => 'Guru Olahraga',
                                'Guru Mengaji' => 'Guru Mengaji',
                            ])
                            ->label('Jabatan / Posisi'),

                        Select::make('status')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Cuti' => 'Cuti',
                                'Pindah' => 'Pindah',
                                'Pensiun' => 'Pensiun',
                            ])
                            ->default('Aktif'),

                        DatePicker::make('tanggal_masuk')->label('Tanggal Masuk'),
                    ])
                    ->columns(2),


            ])
                ->skippable()
                ->columnSpanFull(),
        ];
    }
}
