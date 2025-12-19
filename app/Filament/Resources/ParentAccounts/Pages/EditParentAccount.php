<?php

namespace App\Filament\Resources\ParentAccounts\Pages;

use App\Filament\Resources\ParentAccounts\ParentAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditParentAccount extends EditRecord
{
    protected static string $resource = ParentAccountResource::class;

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
