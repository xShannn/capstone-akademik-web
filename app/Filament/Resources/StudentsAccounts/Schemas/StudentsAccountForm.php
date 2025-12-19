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
            

                // SECTION 2: OTOMATIS GENERATE AKUN ORANG TUA
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
