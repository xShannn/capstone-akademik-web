<?php

namespace App\Filament\Resources\PaymentGuides\Pages;

use App\Filament\Resources\PaymentGuides\PaymentGuideResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPaymentGuide extends EditRecord
{
    protected static string $resource = PaymentGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return "Data '{$this->record->name}' berhasil diperbarui";
    }
}
