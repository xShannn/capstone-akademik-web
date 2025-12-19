<?php

namespace App\Filament\Resources\StudentsAccounts\Tables;

use App\Models\User;
use App\Models\Classroom;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\ParentAccounts\ParentAccountResource;

class StudentsAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->label('NIS')
                    ->description(fn(User $record): string => $record->name),

                TextColumn::make('student.nama_lengkap')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Lengkap')
                    ->placeholder('-'),

                TextColumn::make('student.classroom.nama_kelas')
                    ->label('Kelas')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->student && $record->student->classroom) {
                            return "Kelas {$record->student->classroom->tingkat} - {$record->student->classroom->nama_kelas}";
                        }
                        return '-';
                    })
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('student.status_aktif')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Lulus' => 'info',
                        'Cuti' => 'warning',
                        'Pindah' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status Siswa'),

                BadgeColumn::make('has_parent_account')
                    ->label('Akun Ortu')
                    ->getStateUsing(
                        fn(User $record): string =>
                        $record->student && $record->student->parent_user_id ? 'Sudah' : 'Belum'
                    )
                    ->colors([
                        'success' => 'Sudah',
                        'danger' => 'Belum',
                    ])
                    ->icon(
                        fn($state): string =>
                        $state === 'Sudah' ?  'heroicon-o-check-circle' : 'heroicon-o-x-circle'
                    ),

                TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('classroom')
                    ->relationship('student.classroom', 'nama_kelas')
                    ->label('Kelas')
                    ->searchable(),

                SelectFilter::make('status_aktif')
                    ->relationship('student', 'status_aktif')
                    ->label('Status Siswa'),

                TernaryFilter::make('parent_account')
                    ->label('Status Akun Ortu')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah ada')
                    ->falseLabel('Belum ada')
                    ->queries(
                        true: fn($query) => $query->whereHas('student', fn($q) => $q->whereNotNull('parent_user_id')),
                        false: fn($query) => $query->whereHas('student', fn($q) => $q->whereNull('parent_user_id')),
                    ),
            ])

            ->actions([
                // Generate parent account
                Action::make('generateParent')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->label('Buat Akun Ortu')
                    ->action(function (User $record) {
                        // Panggil fungsi generateParentAccount dari Resource
                        $result = \App\Filament\Resources\StudentsAccounts\StudentsAccountResource::generateParentAccount($record);

                        if ($result) {
                            Notification::make()
                                ->title('Akun orang tua berhasil dibuat!')
                                ->body("Username: {$result->username}\nPassword: {$record->username} (NIS anak)")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Gagal membuat akun orang tua')
                                ->body('Siswa sudah memiliki akun orang tua')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(
                        fn(User $record): bool =>
                        $record->student && !$record->student->parent_user_id
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Buat Akun Orang Tua')
                    ->modalDescription('Apakah Anda yakin ingin membuat akun orang tua untuk siswa ini?')
                    ->modalSubmitActionLabel('Buat Akun'),

                // View parent account
                Action::make('viewParent')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->label('Lihat Akun Ortu')
                    ->url(
                        fn(User $record): string =>
                        $record->student && $record->student->parent_user_id
                            ? ParentAccountResource::getUrl('edit', ['record' => $record->student->parent_user_id])
                            : '#'
                    )
                    ->visible(
                        fn(User $record): bool =>
                        $record->student && $record->student->parent_user_id
                    )
                    ->openUrlInNewTab(),

                EditAction::make(),
                DeleteAction::make(),

                // Reset password
                Action::make('resetPassword')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->label('Reset Password')
                    ->action(function (User $record) {
                        $record->update([
                            'password' => Hash::make('password123')
                        ]);

                        Notification::make()
                            ->title('Password berhasil direset!')
                            ->body('Password baru: password123')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reset Password')
                    ->modalDescription('Password akan direset ke: password123')
                    ->modalSubmitActionLabel('Reset'),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    // Bulk generate parent accounts
                    BulkAction::make('bulkGenerateParent')
                        ->icon('heroicon-o-user-group')
                        ->color('success')
                        ->label('Buat Akun Ortu (Bulk)')
                        ->action(function ($records) {
                            $successCount = 0;
                            $alreadyHasCount = 0;
                            $failedCount = 0;

                            foreach ($records as $record) {
                                try {
                                    // Skip jika siswa tidak ada atau sudah punya akun ortu
                                    if (!$record->student || $record->student->parent_user_id) {
                                        $alreadyHasCount++;
                                        continue;
                                    }

                                    // Generate akun ortu
                                    $result = \App\Filament\Resources\StudentsAccounts\StudentsAccountResource::generateParentAccount($record);

                                    if ($result) {
                                        $successCount++;
                                    } else {
                                        $failedCount++;
                                    }
                                } catch (\Exception $e) {
                                    $failedCount++;
                                }
                            }

                            Notification::make()
                                ->title('Proses Selesai')
                                ->success()
                                ->body("Berhasil: {$successCount}\nSudah ada: {$alreadyHasCount}\nGagal: {$failedCount}")
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Buat Akun Orang Tua Massal')
                        ->modalDescription('Membuat akun orang tua untuk siswa yang belum memiliki')
                        ->modalSubmitActionLabel('Proses')
                        ->deselectRecordsAfterCompletion(),

                    // Bulk reset password
                    BulkAction::make('resetPassword')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->label('Reset Password (Bulk)')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'password' => Hash::make('password123')
                                ]);
                            }

                            Notification::make()
                                ->title('Password berhasil direset')
                                ->body('Password baru: password123 untuk semua akun terpilih')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Reset Password (Bulk)')
                        ->modalDescription('Password akan direset ke: password123 untuk semua akun terpilih')
                        ->modalSubmitActionLabel('Reset'),

                    DeleteBulkAction::make(),
                ]),
            ])

            ->emptyStateActions([
                CreateAction::make()
                    ->label('Tambah Akun Siswa'),
            ])

            ->headerActions([
                // Header action untuk generate akun ortu untuk semua siswa yang belum punya
                Action::make('generateAllParentAccounts')
                    ->label('Generate Semua Akun Ortu')
                    ->icon('heroicon-o-user-group')
                    ->color('primary')
                    ->action(function () {
                        $studentsWithoutParent = User::where('role', 'student')
                            ->whereHas('student', function ($query) {
                                $query->whereNull('parent_user_id');
                            })
                            ->get();

                        $successCount = 0;
                        $failedCount = 0;

                        foreach ($studentsWithoutParent as $studentUser) {
                            try {
                                $result = \App\Filament\Resources\StudentsAccounts\StudentsAccountResource::generateParentAccount($studentUser);
                                if ($result) {
                                    $successCount++;
                                } else {
                                    $failedCount++;
                                }
                            } catch (\Exception $e) {
                                $failedCount++;
                            }
                        }

                        Notification::make()
                            ->title('Proses Generate Selesai')
                            ->success()
                            ->body("Berhasil dibuat: {$successCount} akun orang tua\nGagal: {$failedCount}")
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Generate Semua Akun Orang Tua')
                    ->modalDescription('Membuat akun orang tua untuk SEMUA siswa yang belum memiliki')
                    ->modalSubmitActionLabel('Generate Semua'),
            ]);
    }
}
