<?php

namespace App\Filament\Resources\TeachersAccounts\Pages;

use App\Filament\Resources\TeachersAccounts\TeachersAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeachersAccounts extends ListRecords
{
    protected static string $resource = TeachersAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
