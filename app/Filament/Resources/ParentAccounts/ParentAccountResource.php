<?php

namespace App\Filament\Resources\ParentAccounts;

use App\Filament\Resources\ParentAccounts\Pages\CreateParentAccount;
use App\Filament\Resources\ParentAccounts\Pages\EditParentAccount;
use App\Filament\Resources\ParentAccounts\Pages\ListParentAccounts;
use App\Filament\Resources\ParentAccounts\Pages\ViewParentAccount;
use App\Filament\Resources\ParentAccounts\Schemas\ParentAccountForm;
use App\Filament\Resources\ParentAccounts\Schemas\ParentAccountInfolist;
use App\Filament\Resources\ParentAccounts\Tables\ParentAccountsTable;
use App\Models\ParentAccount;
use App\Models\Student;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ParentAccountResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Akun';
    protected static ?string $navigationLabel = 'Akun Orang Tua';

    protected static ?string $modelLabel = 'Akun Orang Tua';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('role', 'parent');
    }

    protected static ?string $recordTitleAttribute = 'nama_ayah';

    public static function form(Schema $schema): Schema
    {
        return ParentAccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ParentAccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParentAccountsTable::configure($table);
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
            'index' => ListParentAccounts::route('/'),
            'create' => CreateParentAccount::route('/create'),
            'view' => ViewParentAccount::route('/{record}'),
            'edit' => EditParentAccount::route('/{record}/edit'),
        ];
    }
}
