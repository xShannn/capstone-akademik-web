<?php

namespace App\Filament\Resources\StudentsAccounts\Pages;

use App\Filament\Resources\StudentsAccounts\StudentsAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentsAccount extends ViewRecord
{
    protected static string $resource = StudentsAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
