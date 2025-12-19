<?php

namespace App\Filament\Resources\PaymentGuides\Pages;

use App\Filament\Resources\PaymentGuides\PaymentGuideResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentGuides extends ListRecords
{
    protected static string $resource = PaymentGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
