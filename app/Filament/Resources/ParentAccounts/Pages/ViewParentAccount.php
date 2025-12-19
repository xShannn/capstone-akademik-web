<?php

namespace App\Filament\Resources\ParentAccounts\Pages;

use App\Filament\Resources\ParentAccounts\ParentAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewParentAccount extends ViewRecord
{
    protected static string $resource = ParentAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
