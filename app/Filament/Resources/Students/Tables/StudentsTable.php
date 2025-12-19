<?php

namespace App\Filament\Resources\Students\Tables;

use App\Models\User;
use Filament\Tables;
use App\Models\Student;
use Filament\Tables\Table;
use Filament\Actions\Action;
// use Tables\Actions\CreateAction;
use TextColumn\TextColumnSize;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class StudentsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nis')
                    ->label('NIS')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Murid')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->colors([
                        'info' => fn($state) => $state === 'Laki-laki',
                        'warning' => fn($state) => $state === 'Perempuan',
                    ]),

                TextColumn::make('tahun_masuk')
                    ->label('Tahun Masuk')
                    ->sortable(),

                BadgeColumn::make('status_aktif')
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

                BadgeColumn::make('has_account')
                    ->label('Akun')
                    ->getStateUsing(
                        fn(Student $record): string =>
                        $record->user_id ? 'Sudah' : 'Belum'
                    )
                    ->colors([
                        'success' => 'Sudah',
                        'danger' => 'Belum',
                    ]),
            ])

            ->filters([
                Filter::make('has_account')
                    ->label('Sudah Punya Akun')
                    ->query(fn($query) => $query->whereNotNull('user_id')),

                Filter::make('no_account')
                    ->label('Belum Punya Akun')
                    ->query(fn($query) => $query->whereNull('user_id')),

                Filter::make('status_aktif')
                    ->form([
                        Select::make('status')
                            ->label('Status Aktif')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Lulus' => 'Lulus',
                                'Pindah' => 'Pindah',
                                'Cuti' => 'Cuti',
                            ])
                            ->multiple()
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['status']) || empty($data['status'])) {
                            return $query;
                        }

                        return $query->whereIn('status_aktif', $data['status']);
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!isset($data['status']) || empty($data['status'])) {
                            return null;
                        }

                        return 'Status: ' . implode(', ', $data['status']);
                    }),
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
                    ->action(function (Student $record) {
                        // Cek apakah sudah punya akun
                        if ($record->user_id) {
                            Notification::make()
                                ->title('Sudah Memiliki Akun')
                                ->warning()
                                ->body("{$record->nama_lengkap} sudah memiliki akun.")
                                ->send();
                            return;
                        }

                        // Cek apakah NISN/NIS ada
                        $username = $record->nisn ?? $record->nis;
                        if (!$username) {
                            Notification::make()
                                ->title('NISN/NIS Tidak Ditemukan')
                                ->danger()
                                ->body('Lengkapi NISN atau NIS murid terlebih dahulu.')
                                ->send();
                            return;
                        }

                        // Cek apakah email sudah digunakan
                        if ($record->email) {
                            $existingUser = User::where('email', $record->email)->first();
                            if ($existingUser) {
                                // Jika email sudah digunakan, update student dengan user_id yang sudah ada
                                $record->update(['user_id' => $existingUser->id]);

                                Notification::make()
                                    ->title('Akun Sudah Ada')
                                    ->warning()
                                    ->body("{$record->nama_lengkap} sudah memiliki akun dengan email: {$record->email}")
                                    ->send();
                                return;
                            }
                        }

                        // Cek apakah username (NISN/NIS) sudah digunakan
                        if (User::where('username', $username)->exists()) {
                            Notification::make()
                                ->title('Username Sudah Digunakan')
                                ->danger()
                                ->body("Username '{$username}' sudah digunakan oleh user lain.")
                                ->send();
                            return;
                        }

                        // Buat user baru
                        try {
                            $user = User::create([
                                'name' => $record->nama_lengkap,
                                'email' => $record->email ?: $username . '@siswa.sekolah.sch.id',
                                'username' => $username,
                                'password' => Hash::make('password123'),
                                'role' => 'student',
                            ]);

                            $record->update(['user_id' => $user->id]);

                            Notification::make()
                                ->title('Akun Berhasil Dibuat')
                                ->success()
                                ->body("Username: {$username}\nPassword: password123")
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Membuat Akun')
                                ->danger()
                                ->body('Terjadi kesalahan saat membuat akun: ' . $e->getMessage())
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Buat Akun Murid')
                    ->modalSubheading("Username: NISN/NIS\nPassword default: password123")
                    ->modalButton('Ya, Buat Akun')
                    ->hidden(fn(Student $record) => $record->user_id),

                EditAction::make(),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Murid')
                    ->modalSubheading('Apakah kamu yakin ingin menghapus data murid ini?')
                    ->modalButton('Ya, Hapus')
                    ->successNotificationTitle('Data murid berhasil dihapus!')
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

                                    // Cek username (NISN/NIS)
                                    $username = $record->nisn ?? $record->nis;
                                    if (!$username) {
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
                                    if (User::where('username', $username)->exists()) {
                                        $failedCount++;
                                        continue;
                                    }

                                    // Buat user baru
                                    $user = User::create([
                                        'name' => $record->nama_lengkap,
                                        'email' => $record->email ?: $username . '@siswa.sekolah.sch.id',
                                        'username' => $username,
                                        'password' => Hash::make('password123'),
                                        'role' => 'student',
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
                        ->modalSubheading("Membuat akun untuk murid yang belum memiliki akun")
                        ->modalButton('Ya, Proses')
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
