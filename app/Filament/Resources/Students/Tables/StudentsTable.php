<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Tables;
use App\Models\Student;
use Filament\Tables\Table;
use Filament\Actions\Action;
use TextColumn\TextColumnSize;
// use Tables\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
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
                    ->label('Jenis Kelamin'),

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
            ])

            ->filters([
                Filter::make('sort_created_at')
                    ->form([
                        Select::make('direction')
                            ->label('Urutkan berdasarkan tanggal dibuat')
                            ->options([
                                'asc' => 'Terlama → Terbaru (ASC)',
                                'desc' => 'Terbaru → Terlama (DESC)',
                            ])
                            ->default('desc')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['direction'])) {
                            return $query;
                        }

                        return $query->reorder('created_at', $data['direction']);
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!isset($data['direction'])) {
                            return null;
                        }

                        return 'Urutan: ' . ($data['direction'] === 'asc' ? 'Terlama → Terbaru' : 'Terbaru → Terlama');
                    }),
            ])

            ->recordActions([
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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
