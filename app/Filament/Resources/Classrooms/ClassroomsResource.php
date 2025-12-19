<?php

namespace App\Filament\Resources\Classrooms;

use BackedEnum;
use App\Models\Classroom;
use App\Models\Classrooms;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Classrooms\Pages\EditClassrooms;
use App\Filament\Resources\Classrooms\Pages\ListClassrooms;
use App\Filament\Resources\Classrooms\Pages\ViewClassrooms;
use App\Filament\Resources\Classrooms\Pages\CreateClassrooms;
use App\Filament\Resources\Classrooms\Schemas\ClassroomsForm;
use App\Filament\Resources\Classrooms\Tables\ClassroomsTable;
use App\Filament\Resources\Classrooms\Schemas\ClassroomsInfolist;
use UnitEnum;

class ClassroomsResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $modelLabel = 'Kelas';

    // protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?string $recordTitleAttribute = 'nama_kelas';

    public static function form(Schema $schema): Schema
    {
        return ClassroomsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClassroomsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassroomsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassrooms::route('/'),
            'create' => CreateClassrooms::route('/create'),
            'view' => ViewClassrooms::route('/{record}'),
            'edit' => EditClassrooms::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'primary' : 'gray';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama_kelas', 'waliKelas.nama', 'guruNgaji.nama', 'guruOlahraga.nama'];
    }
}
