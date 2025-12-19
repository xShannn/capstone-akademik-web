<?php

namespace App\Filament\Resources\Schedules\Tables;

use App\Models\Schedule;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

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
                    ->sortable()
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Mata Pelajaran')
                    ->searchable(),

                TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->time(),

                TextColumn::make('end_time')
                    ->label('Waktu Selesai')
                    ->time(),
            ])
            ->filters([
                SelectFilter::make('day')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                    ])
                    ->placeholder('Semua Hari')
                    ->searchable(),
                SelectFilter::make('subject')
                    ->label('Mata Pelajaran')
                    ->options(function () {
                        // Ambil semua mata pelajaran unik dari database
                        return Schedule::query()
                            ->distinct('subject')
                            ->orderBy('subject')
                            ->pluck('subject', 'subject')
                            ->toArray();
                    })
                    ->placeholder('Semua Mata Pelajaran')
                    ->multiple() // Bisa pilih banyak mapel
                    ->searchable(),
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
