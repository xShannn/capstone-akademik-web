<?php

namespace App\Filament\Resources\TeachersAccounts\Pages;

use App\Filament\Resources\TeachersAccounts\TeachersAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTeachersAccount extends EditRecord
{
    protected static string $resource = TeachersAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
