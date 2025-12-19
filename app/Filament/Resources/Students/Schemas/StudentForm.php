<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Wizard\Step;

class StudentForm
{
    public static function schema(): array
    {
        return [
            Wizard::make([

                Step::make('Identitas Murid')
                    ->schema([
                        TextInput::make('nisn')->label('NISN')->required(),
                        TextInput::make('nis')->label('NIS')->required(),

                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required(),

                        Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),

                        TextInput::make('tempat_lahir'),
                        DatePicker::make('tanggal_lahir')->required(),
                        Select::make('agama')
                            ->label('Agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Konghucu' => 'Konghucu',
                            ])
                            ->searchable()
                            ->required(),
                        TextInput::make('nik')->label('NIK'),
                    ])
                    ->columns(2),

                Step::make('Kontak & Alamat')
                    ->schema([
                        TextInput::make('nomor_telepon')->label('Nomor Telepon'),
                        TextInput::make('email')->email(),

                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull()
                            ->required(),

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


                Step::make('Data Akademik')
                    ->schema([
                        TextInput::make('tahun_masuk')->numeric(),
                        Select::make('status_aktif')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Lulus'  => 'Lulus',
                                'Pindah' => 'Pindah',
                                'Cuti'   => 'Cuti',
                            ])
                            ->default('Aktif'),
                    ]),

                Step::make('Data Orang Tua / Wali')
                    ->schema([
                        TextInput::make('nama_ayah')->required(),
                        TextInput::make('pekerjaan_ayah'),
                        TextInput::make('nama_ibu')->required(),
                        TextInput::make('pekerjaan_ibu'),
                        TextInput::make('nomor_telepon_ortu')->label('Nomor Telepon Orang Tua')->required(),
                    ])
                    ->columns(2),

            ])
                ->skippable()
                ->columnSpanFull(),
        ];
    }
}
