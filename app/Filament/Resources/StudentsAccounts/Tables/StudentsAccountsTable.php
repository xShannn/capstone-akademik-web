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
                    ->label('Status'),

                TextColumn::make('student.parent_user_id')
                    ->label('Akun Ortu')
                    ->formatStateUsing(fn($state): string => $state ? '✓' : '✗')
                    ->badge()
                    ->color(fn($state): string => $state ? 'success' : 'danger'),

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

                TernaryFilter::make('has_parent_account')
                    ->label('Memiliki Akun Ortu')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah ada akun ortu')
                    ->falseLabel('Belum ada akun ortu')
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
                    ->label('Generate Akun Ortu')
                    ->action(function (User $record) {
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
                    ->modalHeading('Generate Akun Orang Tua')
                    ->modalDescription('Apakah Anda yakin ingin membuat akun orang tua untuk siswa ini?')
                    ->modalSubmitActionLabel('Generate'),

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
                    DeleteBulkAction::make(),

                    BulkAction::make('resetPassword')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->label('Reset Password')
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
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Tambah Akun Siswa'),
            ]);
    }
}
