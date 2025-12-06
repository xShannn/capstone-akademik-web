<?php

namespace App\Filament\Resources\StudentsAccounts\Pages;

use App\Filament\Resources\StudentsAccounts\StudentsAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentsAccounts extends ListRecords
{
    protected static string $resource = StudentsAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
