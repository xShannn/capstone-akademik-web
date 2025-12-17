<?php

namespace App\Filament\Resources\TeachersAccounts\Pages;

use App\Filament\Pages\BaseCreateRecord;
use App\Filament\Resources\TeachersAccounts\TeachersAccountResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class CreateTeachersAccount extends BaseCreateRecord
{
    protected static string $resource = TeachersAccountResource::class;

    protected function getFormSchema(): array
    {
        return [
            Select::make('teacher_id')
                ->required()
                ->label('Pilih Guru')
                ->relationship('teacher', 'nama_lengkap')
                ->searchable()
                ->preload()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $teacher = \App\Models\Teacher::find($state);

                    if ($teacher) {
                        $set('name', $teacher->nama_lengkap);
                        $set('username', $teacher->nip);
                        $set('email', $teacher->email ?: $teacher->nip . '@sekolah.sch.id');
                    }
                }),

            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->disabled(),

            TextInput::make('username')
                ->label('Username (NIP)')
                ->required()
                ->unique(\App\Models\User::class, 'username'),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(\App\Models\User::class, 'email'),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->default('password123')
                ->helperText('Password default: password123')
                ->dehydrateStateUsing(fn($state) => Hash::make($state)),

            Hidden::make('role')
                ->default('teacher'),
        ];
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Akun Guru Berhasil Ditambahkan')
            ->body("Username: {$this->record->username}\nPassword: password123")
            ->duration(5000);
    }
}
