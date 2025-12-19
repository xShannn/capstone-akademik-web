<?php

namespace App\Filament\Resources\SchoolYears\Pages;

use App\Filament\Pages\BaseCreateRecord;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SchoolYears\SchoolYearResource;

class CreateSchoolYear extends BaseCreateRecord
{
    protected static string $resource = SchoolYearResource::class;
}
