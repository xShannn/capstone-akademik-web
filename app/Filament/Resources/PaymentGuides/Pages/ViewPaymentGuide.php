<?php

namespace App\Filament\Resources\PaymentGuides\Pages;

use App\Filament\Resources\PaymentGuides\PaymentGuideResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentGuide extends ViewRecord
{
    protected static string $resource = PaymentGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
