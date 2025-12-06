<?php

namespace App\Filament\Resources\TeachersAccounts\Schemas;

use App\Models\User;
use App\Models\Teacher;
use Filament\Schemas\Schema;
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
                    ->searchable(),

                TextInput::make('name')
                    ->label('Nama Akun')
                    ->required(),

                TextInput::make('email')
                    ->label('Email Login')
                    ->email()
                    ->unique(User::class, 'email')
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn($state) => bcrypt($state)),
            ]);
    }
}
