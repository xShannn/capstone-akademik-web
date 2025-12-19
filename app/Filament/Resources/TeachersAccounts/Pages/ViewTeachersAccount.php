<?php

namespace App\Filament\Resources\TeachersAccounts\Pages;

use App\Filament\Resources\TeachersAccounts\TeachersAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTeachersAccount extends ViewRecord
{
    protected static string $resource = TeachersAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
