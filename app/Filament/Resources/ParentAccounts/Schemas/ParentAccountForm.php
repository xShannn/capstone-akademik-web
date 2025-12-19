<?php

namespace App\Filament\Resources\ParentAccounts\Schemas;

use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ParentAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }
}
