<?php

namespace App\Filament\Resources\SchoolYears\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class SchoolYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tahun Ajaran')
                    ->placeholder('2024/2025')
                    ->required(),

                Select::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->required(),

                DatePicker::make('start_date')->label('Mulai')->required(),
                DatePicker::make('end_date')->label('Selesai')->required(),

                Toggle::make('is_active')
                    ->label('Tahun Ajaran Aktif')
                    ->reactive()
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state === true) {
                            // set semua lainnya nonaktif
                            \App\Models\SchoolYear::where('id', '!=', $record?->id)
                                ->update(['is_active' => false]);
                        }
                    })
                    ->default(false),
            ]);
    }
}
