<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('classroom.nama_kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('teacher.nama')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('day')
                    ->label('Hari')
                    ->sortable(),

                TextColumn::make('subject')
                    ->label('Mata Pelajaran'),

                TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->time(),

                TextColumn::make('end_time')
                    ->label('Waktu Selesai')
                    ->time(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
