<?php

namespace App\Filament\Resources\ParentAccounts\Pages;

use App\Filament\Pages\BaseCreateRecord;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ParentAccounts\ParentAccountResource;

class CreateParentAccount extends BaseCreateRecord
{
    protected static string $resource = ParentAccountResource::class;
}
