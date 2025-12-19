<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

abstract class BaseCreateRecord extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title($this->getResource()::getModelLabel() . ' berhasil ditambahkan')
            ->duration(3000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan'),

            $this->getCreateAnotherFormAction()
                ->label('Simpan & Tambah Baru')
                ->color('gray'),

            $this->getCancelFormAction()
                ->label('Batal')
                ->color('gray'),
        ];
    }
}
