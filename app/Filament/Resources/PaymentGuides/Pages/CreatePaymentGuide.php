<?php

namespace App\Filament\Resources\PaymentGuides\Pages;

use App\Filament\Pages\BaseCreateRecord;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PaymentGuides\PaymentGuideResource;

class CreatePaymentGuide extends BaseCreateRecord
{
    protected static string $resource = PaymentGuideResource::class;
}
