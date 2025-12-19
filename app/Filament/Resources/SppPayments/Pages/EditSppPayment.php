<?php

namespace App\Filament\Resources\SppPayments\Pages;

use App\Filament\Resources\SppPayments\SppPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSppPayment extends EditRecord
{
    protected static string $resource = SppPaymentResource::class;

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
