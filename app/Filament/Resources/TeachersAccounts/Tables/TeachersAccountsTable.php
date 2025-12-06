<?php

namespace App\Filament\Resources\TeachersAccounts\Tables;

use App\Models\User;
use App\Models\Teacher;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TeachersAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->label('Username'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                TextColumn::make('teacher.nip')
                    ->searchable()
                    ->label('NIP'),
                TextColumn::make('teacher.jabatan')
                    ->label('Jabatan'),
                TextColumn::make('teacher.status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Cuti' => 'warning',
                        'Pindah', 'Pensiun' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->relationship('teacher', 'status')
                    ->label('Status Guru'),
                SelectFilter::make('jabatan')
                    ->relationship('teacher', 'jabatan')
                    ->label('Jabatan'),
            ])
            ->recordActions([
                Action::make('viewTeacher')
                    ->label('Lihat Data Guru')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn(User $record): string =>
                        $record->teacher
                            ? route('filament.admin.resources.teachers.view', $record->teacher->id)
                            : '#'
                    ),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
