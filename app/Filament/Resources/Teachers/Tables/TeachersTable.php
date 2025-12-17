<?php

namespace App\Filament\Resources\Teachers\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')
                    ->searchable(),
                TextColumn::make('nama_lengkap')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->badge()
                    ->colors([
                        'info' => fn($state) => $state === 'Laki-laki',
                        'warning'  => fn($state) => $state === 'Perempuan',
                    ]),
                TextColumn::make('agama')
                    ->searchable(),
                TextColumn::make('nik')
                    ->searchable(),
                TextColumn::make('nomor_telepon')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('jabatan')
                    ->badge(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Aktif' => 'Aktif',
                        'Lulus' => 'Lulus',
                        'Pindah' => 'Pindah',
                        'Cuti' => 'Cuti',
                        default => $state,
                    })
                    ->colors([
                        'success' => fn($state) => $state === 'Aktif',
                        'warning' => fn($state) => $state === 'Cuti',
                        'danger'  => fn($state) => in_array($state, ['Pindah', 'Lulus']),
                    ]),
                TextColumn::make('tanggal_masuk')
                    ->date()
                    ->sortable(),
                BadgeColumn::make('has_account')
                    ->label('Akun')
                    ->getStateUsing(
                        fn(Teacher $record): string =>
                        $record->user_id ? 'Sudah' : 'Belum'
                    )
                    ->colors([
                        'success' => 'Sudah',
                        'danger' => 'Belum',
                    ]),
            ])

            ->filters([
                Filter::make('is_featured')
                    ->query(fn(Builder $query): Builder => $query->where('is_featured', true)),
                Filter::make('has_account')
                    ->label('Sudah Punya Akun')
                    ->query(fn($query) => $query->whereNotNull('user_id')),
                Filter::make('no_account')
                    ->label('Belum Punya Akun')
                    ->query(fn($query) => $query->whereNull('user_id')),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )

            ->recordActions([
                Action::make('generateAccount')
                    ->label('Buat Akun')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->action(function (Teacher $record) {
                        // Cek apakah sudah punya akun
                        if ($record->user_id) {
                            Notification::make()
                                ->title('Sudah Memiliki Akun')
                                ->warning()
                                ->body("{$record->nama_lengkap} sudah memiliki akun.")
                                ->send();
                            return;
                        }

                        // Cek apakah NIP ada
                        if (!$record->nip) {
                            Notification::make()
                                ->title('NIP Tidak Ditemukan')
                                ->danger()
                                ->body('Lengkapi NIP guru terlebih dahulu.')
                                ->send();
                            return;
                        }

                        // Cek apakah email sudah digunakan
                        if ($record->email) {
                            $existingUser = User::where('email', $record->email)->first();
                            if ($existingUser) {
                                // Jika email sudah digunakan, update teacher dengan user_id yang sudah ada
                                $record->update(['user_id' => $existingUser->id]);

                                Notification::make()
                                    ->title('Akun Sudah Ada')
                                    ->warning()
                                    ->body("{$record->nama_lengkap} sudah memiliki akun dengan email: {$record->email}")
                                    ->send();
                                return;
                            }
                        }

                        // Cek apakah username (NIP) sudah digunakan
                        if (User::where('username', $record->nip)->exists()) {
                            Notification::make()
                                ->title('Username Sudah Digunakan')
                                ->danger()
                                ->body("Username '{$record->nip}' sudah digunakan oleh user lain.")
                                ->send();
                            return;
                        }

                        // Buat user baru
                        try {
                            $user = User::create([
                                'name' => $record->nama_lengkap,
                                'email' => $record->email ?: $record->nip . '@sekolah.app',
                                'username' => $record->nip,
                                'password' => Hash::make('password123'),
                                'role' => 'teacher',
                            ]);

                            $record->update(['user_id' => $user->id]);

                            Notification::make()
                                ->title('Akun Berhasil Dibuat')
                                ->success()
                                ->body("Username: {$record->nip}\nPassword: password123")
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Membuat Akun')
                                ->danger()
                                ->body('Terjadi kesalahan saat membuat akun.')
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Buat Akun Guru')
                    ->modalSubheading("Username: NIP\nPassword default: password123")
                    ->modalButton('Ya, Buat Akun')
                    ->hidden(fn(Teacher $record) => $record->user_id),

                EditAction::make(),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Guru')
                    ->modalSubheading('Apakah kamu yakin ingin menghapus data guru ini?')
                    ->modalButton('Ya, Hapus')
                    ->successNotificationTitle('Data guru berhasil dihapus!')
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('bulkGenerateAccounts')
                        ->label('Buat Akun')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->action(function ($records) {
                            $successCount = 0;
                            $failedCount = 0;
                            $alreadyHasAccount = 0;

                            foreach ($records as $record) {
                                try {
                                    // Skip jika sudah punya akun
                                    if ($record->user_id) {
                                        $alreadyHasAccount++;
                                        continue;
                                    }

                                    // Skip jika tidak ada NIP
                                    if (!$record->nip) {
                                        $failedCount++;
                                        continue;
                                    }

                                    // Cek jika email sudah ada
                                    if ($record->email) {
                                        $existingUser = User::where('email', $record->email)->first();
                                        if ($existingUser) {
                                            $record->update(['user_id' => $existingUser->id]);
                                            $alreadyHasAccount++;
                                            continue;
                                        }
                                    }

                                    // Cek username duplikat
                                    if (User::where('username', $record->nip)->exists()) {
                                        $failedCount++;
                                        continue;
                                    }

                                    // Buat user baru
                                    $user = User::create([
                                        'name' => $record->nama_lengkap,
                                        'email' => $record->email ?: $record->nip . '@sekolah.app',
                                        'username' => $record->nip,
                                        'password' => Hash::make('password123'),
                                        'role' => 'teacher',
                                    ]);

                                    $record->update(['user_id' => $user->id]);
                                    $successCount++;
                                } catch (\Exception $e) {
                                    $failedCount++;
                                }
                            }

                            Notification::make()
                                ->title('Proses Selesai')
                                ->success()
                                ->body("Berhasil: {$successCount}\nSudah ada: {$alreadyHasAccount}\nGagal: {$failedCount}")
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Buat Akun Massal')
                        ->modalSubheading("Membuat akun untuk guru yang belum memiliki akun")
                        ->modalButton('Ya, Proses')
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

// namespace App\Filament\Resources\Teachers\Tables;

// use Filament\Tables\Table;
// use Filament\Actions\Action;
// use Filament\Actions\EditAction;
// use Filament\Support\Enums\Width;
// use Filament\Actions\DeleteAction;
// use Filament\Tables\Filters\Filter;
// use Filament\Actions\BulkActionGroup;
// use Filament\Actions\DeleteBulkAction;
// use Filament\Forms\Components\Builder;
// use Filament\Tables\Columns\TextColumn;
// use Filament\Tables\Columns\BadgeColumn;

// class TeachersTable
// {
//     public static function configure(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 TextColumn::make('nip')
//                     ->searchable(),
//                 TextColumn::make('nama_lengkap')
//                     ->searchable(),
//                 TextColumn::make('jenis_kelamin')
//                     ->badge(),
//                 TextColumn::make('agama')
//                     ->searchable(),
//                 TextColumn::make('nik')
//                     ->searchable(),
//                 TextColumn::make('nomor_telepon')
//                     ->searchable(),
//                 TextColumn::make('email')
//                     ->label('Email address')
//                     ->searchable(),
//                 TextColumn::make('jabatan')
//                     ->badge(),
//                 BadgeColumn::make('status')
//                     ->label('Status')
//                     ->formatStateUsing(fn($state) => match ($state) {
//                         'Aktif' => 'Aktif',
//                         'Lulus' => 'Lulus',
//                         'Pindah' => 'Pindah',
//                         'Cuti' => 'Cuti',
//                         default => $state,
//                     })
//                     ->colors([
//                         'success' => fn($state) => $state === 'Aktif',
//                         'warning' => fn($state) => $state === 'Cuti',
//                         'danger'  => fn($state) => in_array($state, ['Pindah', 'Lulus']),
//                     ]),
//                 TextColumn::make('tanggal_masuk')
//                     ->date()
//                     ->sortable(),
//             ])

//             ->filters([
//                 Filter::make('is_featured')
//                     ->query(fn(Builder $query): Builder => $query->where('is_featured', true))
//             ])
//             ->filtersTriggerAction(
//                 fn(Action $action) => $action
//                     ->button()
//                     ->label('Filter'),
//             )


//             ->recordActions([
//                 EditAction::make(),
//                 DeleteAction::make()
//                     ->requiresConfirmation()
//                     ->modalHeading('Hapus Data Guru')
//                     ->modalSubheading('Apakah kamu yakin ingin menghapus data guru ini?')
//                     ->modalButton('Ya, Hapus')
//                     ->successNotificationTitle('Data guru berhasil dihapus!')
//                     ->color('danger')
//                     ->icon('heroicon-o-trash'),
//             ])
//             ->toolbarActions([
//                 BulkActionGroup::make([
//                     DeleteBulkAction::make(),
//                 ]),
//             ]);
//     }
// }
