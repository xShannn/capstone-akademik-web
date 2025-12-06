<?php

namespace App\Filament\Resources\Classrooms\Tables;

use App\Models\Classroom;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ClassroomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->formatStateUsing(fn($state) => "Kelas {$state}")
                    ->sortable()
                    ->searchable(),

                TextColumn::make('waliKelas.nama_lengkap')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('guruNgaji.nama_lengkap')
                    ->label('Guru Ngaji')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('guruOlahraga.nama_lengkap')
                    ->label('Guru Olahraga')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('students_count')
                    ->label('Jumlah Siswa')
                    ->counts('students')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                // ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tingkat')
                    ->label('Tingkat Kelas')
                    ->options([
                        1 => 'Kelas 1',
                        2 => 'Kelas 2',
                        3 => 'Kelas 3',
                        4 => 'Kelas 4',
                        5 => 'Kelas 5',
                        6 => 'Kelas 6',
                    ])
                    ->multiple()
                    ->searchable(),

                SelectFilter::make('wali_kelas_id')
                    ->label('Wali Kelas')
                    ->relationship('waliKelas', 'nama_lengkap')
                    ->searchable()
                    ->preload(),

                Filter::make('has_no_wali_kelas')
                    ->label('Tanpa Wali Kelas')
                    ->query(fn(Builder $query): Builder => $query->whereNull('wali_kelas_id')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Classroom $record) {
                        if ($record->students()->count() > 0) {
                            $action->cancel();
                            $action->failureNotificationTitle('Tidak dapat menghapus kelas yang masih memiliki siswa.');
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, $records) {
                            $recordsWithStudents = $records->filter(fn($record) => $record->students()->count() > 0);

                            if ($recordsWithStudents->count() > 0) {
                                $action->cancel();
                                $action->failureNotificationTitle(
                                    'Terdapat ' . $recordsWithStudents->count() . ' kelas yang masih memiliki siswa.'
                                );
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->defaultSort('tingkat', 'asc');
    }
}
