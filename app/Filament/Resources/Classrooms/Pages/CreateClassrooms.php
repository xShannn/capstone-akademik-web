<?php

namespace App\Filament\Resources\Classrooms\Pages;

use App\Filament\Pages\BaseCreateRecord;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Classrooms\ClassroomsResource;

class CreateClassrooms extends BaseCreateRecord
{
    protected static string $resource = ClassroomsResource::class;
}
