<?php

namespace App\Filament\Resources\Students;

use table;
use BackedEnum;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Form;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\CreateStudent;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema(
            \App\Filament\Resources\Students\Schemas\StudentForm::schema()
        );
    }


    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return \App\Filament\Resources\Students\Tables\StudentsTable::table($table);
    }



    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
