<?php

namespace App\Filament\Resources\StudentsAccounts\Schemas;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;

class StudentsAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECTION 1: AKUN LOGIN SISWA (tabel users)
                Section::make('Informasi Akun Login Siswa')
                    ->description('Data untuk login ke sistem')
                    ->schema([
                        TextInput::make('username')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Username (NIS)')
                            ->helperText('NIS siswa akan digunakan sebagai username login')
                            ->disabled(fn($context) => $context === 'edit')
                            ->dehydrated(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap')
                            ->helperText('Nama yang akan ditampilkan di sistem'),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->label('Email Login')
                            ->default(fn($get) => $get('username') . '@siswa.sch.id')
                            ->helperText('Digunakan untuk reset password'),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->label('Password')
                            ->default('password123')
                            ->helperText('Password default: password123'),
                    ])->columns(2),

                // SECTION 2: BIODATA SISWA (tabel students)
                Section::make('Biodata Siswa')
                    ->description('Data identitas lengkap siswa')
                    ->relationship('student')
                    ->schema([
                        // Data Identitas
                        Group::make()
                            ->schema([
                                TextInput::make('nis')
                                    ->required()
                                    ->unique(Student::class, 'nis', ignoreRecord: true)
                                    ->label('NIS')
                                    ->disabled(fn($context) => $context === 'edit')
                                    ->dehydrated(),

                                TextInput::make('nisn')
                                    ->label('NISN')
                                    ->unique(Student::class, 'nisn', ignoreRecord: true)
                                    ->nullable(),

                                TextInput::make('nama_lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Lengkap'),

                                Select::make('jenis_kelamin')
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                    ])
                                    ->required()
                                    ->label('Jenis Kelamin'),

                                TextInput::make('tempat_lahir')
                                    ->nullable()
                                    ->maxLength(100)
                                    ->label('Tempat Lahir'),

                                DatePicker::make('tanggal_lahir')
                                    ->nullable()
                                    ->label('Tanggal Lahir'),

                                TextInput::make('agama')
                                    ->nullable()
                                    ->maxLength(50)
                                    ->label('Agama'),

                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->unique(Student::class, 'nik', ignoreRecord: true)
                                    ->nullable(),
                            ])->columns(2),

                        // Kontak & Alamat
                        Group::make()
                            ->schema([
                                TextInput::make('nomor_telepon')
                                    ->tel()
                                    ->nullable()
                                    ->label('Nomor Telepon Siswa'),

                                TextInput::make('email')
                                    ->email()
                                    ->nullable()
                                    ->unique(Student::class, 'email', ignoreRecord: true)
                                    ->label('Email Pribadi')
                                    ->helperText('Email untuk keperluan komunikasi (opsional)'),

                                Select::make('classroom_id')
                                    ->label('Kelas')
                                    ->options(Classroom::all()->pluck('nama_kelas', 'id'))
                                    ->getOptionLabelFromRecordUsing(
                                        fn(Classroom $record) =>
                                        "Kelas {$record->tingkat} - {$record->nama_kelas}"
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                TextInput::make('tahun_masuk')
                                    ->numeric()
                                    ->minValue(2000)
                                    ->maxValue(now()->year)
                                    ->nullable()
                                    ->label('Tahun Masuk'),

                                Select::make('status_aktif')
                                    ->options([
                                        'Aktif' => 'Aktif',
                                        'Lulus' => 'Lulus',
                                        'Pindah' => 'Pindah',
                                        'Cuti' => 'Cuti',
                                    ])
                                    ->default('Aktif')
                                    ->label('Status'),
                            ])->columns(2),

                        // Alamat Lengkap
                        Textarea::make('alamat')
                            ->nullable()
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),

                        Group::make()
                            ->schema([
                                TextInput::make('provinsi')
                                    ->nullable()
                                    ->default('Lampung')
                                    ->label('Provinsi'),

                                TextInput::make('kabupaten')
                                    ->nullable()
                                    ->label('Kabupaten/Kota'),

                                TextInput::make('kecamatan')
                                    ->nullable()
                                    ->label('Kecamatan'),

                                TextInput::make('kelurahan')
                                    ->nullable()
                                    ->label('Kelurahan'),

                                TextInput::make('dusun')
                                    ->nullable()
                                    ->label('Dusun'),

                                TextInput::make('kode_pos')
                                    ->nullable()
                                    ->label('Kode Pos'),
                            ])->columns(3),
                    ]),

                // SECTION 3: DATA ORANG TUA/WALI
                Section::make('Data Orang Tua/Wali')
                    ->description('Informasi orang tua/wali siswa')
                    ->relationship('student')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('nama_ayah')
                                    ->nullable()
                                    ->maxLength(100)
                                    ->label('Nama Ayah'),

                                TextInput::make('pekerjaan_ayah')
                                    ->nullable()
                                    ->maxLength(100)
                                    ->label('Pekerjaan Ayah'),

                                TextInput::make('nama_ibu')
                                    ->nullable()
                                    ->maxLength(100)
                                    ->label('Nama Ibu'),

                                TextInput::make('pekerjaan_ibu')
                                    ->nullable()
                                    ->maxLength(100)
                                    ->label('Pekerjaan Ibu'),

                                TextInput::make('nomor_telepon_ortu')
                                    ->tel()
                                    ->nullable()
                                    ->label('Telepon Orang Tua'),
                            ])->columns(2),
                    ]),

                // SECTION 4: OTOMATIS GENERATE AKUN ORANG TUA
                Section::make('Akun Orang Tua')
                    ->description('Akun login untuk orang tua/wali')
                    ->schema([
                        Toggle::make('generate_parent_account')
                            ->label('Generate Akun Orang Tua Otomatis')
                            ->default(true)
                            ->helperText('Sistem akan otomatis membuat akun login untuk orang tua')
                            ->live(),

                        Placeholder::make('parent_account_info')
                            ->label('Informasi Akun Orang Tua')
                            ->content(function ($get) {
                                if ($get('generate_parent_account')) {
                                    $nama = $get('student.nama_lengkap') ?? $get('name');
                                    $nis = $get('student.nis') ?? $get('username');

                                    if ($nama && $nis) {
                                        $parentUsername = strtolower(str_replace(' ', '', $nama)) . '_ortu';
                                        return "Username: {$parentUsername}\nPassword: {$nis}";
                                    }
                                }
                                return 'Akun orang tua akan digenerate otomatis';
                            })
                            ->hidden(fn($get) => !$get('generate_parent_account')),
                    ]),
            ]);
    }
}
