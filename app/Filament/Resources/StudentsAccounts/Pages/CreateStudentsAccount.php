<?php

namespace App\Filament\Resources\StudentsAccounts\Pages;

use App\Filament\Pages\BaseCreateRecord;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StudentsAccounts\StudentsAccountResource;

class CreateStudentsAccount extends BaseCreateRecord
{
    protected static string $resource = StudentsAccountResource::class;
}
