<?php

namespace App\Filament\Resources\SppPayments\Pages;

use App\Filament\Resources\SppPayments\SppPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSppPayments extends ListRecords
{
    protected static string $resource = SppPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
