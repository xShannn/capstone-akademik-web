<?php

namespace App\Filament\Resources\PaymentGuides\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Column;

class PaymentGuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul PDF')
                    ->placeholder('Masukkan Judul')
                    ->required(),


                FileUpload::make('file_path')
                    ->label('Upload File PDF')
                    ->directory('payment-guides')
                    ->acceptedFileTypes(['application/pdf'])
                    ->panelAspectRatio('22:4')
                    ->required(),
                ])
                ->columns(1);
    }
}
