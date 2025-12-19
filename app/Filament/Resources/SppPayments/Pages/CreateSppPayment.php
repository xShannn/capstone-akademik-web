<?php

namespace App\Filament\Resources\SppPayments\Pages;

use App\Filament\Pages\BaseCreateRecord;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SppPayments\SppPaymentResource;

class CreateSppPayment extends BaseCreateRecord
{
    protected static string $resource = SppPaymentResource::class;
}
