<?php

namespace App\Filament\Resources\StudentsAccounts\Pages;

use App\Filament\Resources\StudentsAccounts\StudentsAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentsAccount extends EditRecord
{
    protected static string $resource = StudentsAccountResource::class;

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
