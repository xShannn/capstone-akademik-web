<?php

namespace App\Filament\Resources\SppPayments\Pages;

use App\Filament\Resources\SppPayments\SppPaymentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSppPayment extends ViewRecord
{
    protected static string $resource = SppPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
