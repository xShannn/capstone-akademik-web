<?php

namespace App\Filament\Resources\SppPayments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SppPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'nama_lengkap')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('month')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ])
                    ->required(),

                TextInput::make('year')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(now()->year + 1)
                    ->required(),

                Select::make('status')
                    ->options([
                        'paid' => 'Lunas',
                        'unpaid' => 'Belum Lunas',
                    ])
                    ->required(),

                Textarea::make('note')
                    ->label('Catatan')
                    ->rows(3),
            ]);
    }
}
