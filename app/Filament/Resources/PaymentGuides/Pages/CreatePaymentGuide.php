<?php

namespace App\Filament\Resources\PaymentGuides\Pages;

use App\Filament\Resources\PaymentGuides\PaymentGuideResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentGuide extends CreateRecord
{
    protected static string $resource = PaymentGuideResource::class;
}
