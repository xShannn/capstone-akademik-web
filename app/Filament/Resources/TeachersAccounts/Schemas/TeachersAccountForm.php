<?php

namespace App\Filament\Resources\TeachersAccounts\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TeachersAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('teacher_id')
                    ->required()
                    ->label('Pilih Guru')
                    ->relationship('teacher', 'nama_lengkap')
                    ->searchable()
                    ->preload()
                    ->live() // Ganti reactive() jadi live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (!$state) return;

                        $teacher = \App\Models\Teacher::find($state);

                        if ($teacher) {
                            // Set otomatis nama dari guru
                            $set('name', $teacher->nama_lengkap);

                            // Set email (generate jika tidak ada)
                            if (!$get('email')) {
                                $set('email', $teacher->email ?: $teacher->nip . '@sekolah.sch.id');
                            }

                            // Set username dari NIP guru
                            $set('username', $teacher->nip ?: 'guru_' . $teacher->id);
                        }
                    }),

                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->disabled() // Boleh disabled
                    ->dehydrated() // TAMBAHKAN INI
                    ->helperText('Otomatis terisi dari data guru'),

                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->unique(User::class, 'username', ignoreRecord: true)
                    ->dehydrated() // Pastikan data tersimpan
                    ->helperText('Username untuk login menggunakan NIP guru'),

                TextInput::make('email')
                    ->label('Email Sekolah')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email', ignoreRecord: true)
                    ->dehydrated()
                    ->helperText('Email untuk login dan notifikasi'),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn($operation) => $operation === 'create')
                    ->default('password123')
                    ->dehydrateStateUsing(fn($state) => bcrypt($state))
                    ->helperText(function ($operation) {
                        return $operation === 'create'
                            ? 'Password default: password123'
                            : 'Kosongkan jika tidak ingin mengubah password';
                    }),

                Hidden::make('role')
                    ->default('teacher')
                    ->dehydrated(),
            ]);
    }
}
