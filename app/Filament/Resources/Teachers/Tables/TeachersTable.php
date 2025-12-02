<?php

namespace App\Filament\Resources\Teachers\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

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
                    ->badge(),
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
            ])

            ->filters([
                Filter::make('is_featured')
                    ->query(fn(Builder $query): Builder => $query->where('is_featured', true))
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )


            ->recordActions([
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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
