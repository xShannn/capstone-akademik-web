<?php

namespace App\Filament\Resources\TeachersAccounts;

use BackedEnum;
use App\Models\User;
use App\Models\Teacher;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\TeachersAccount;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\TeachersAccounts\Pages\EditTeachersAccount;
use App\Filament\Resources\TeachersAccounts\Pages\ViewTeachersAccount;
use App\Filament\Resources\TeachersAccounts\Pages\ListTeachersAccounts;
use App\Filament\Resources\TeachersAccounts\Pages\CreateTeachersAccount;
use App\Filament\Resources\TeachersAccounts\Schemas\TeachersAccountForm;
use App\Filament\Resources\TeachersAccounts\Tables\TeachersAccountsTable;
use App\Filament\Resources\TeachersAccounts\Schemas\TeachersAccountInfolist;
use UnitEnum;

class TeachersAccountResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $modelLabel = 'Akun Guru';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Akun';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Akun Guru';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('role', 'teacher');
    }

    public static function form(Schema $schema): Schema
    {
        return TeachersAccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeachersAccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeachersAccountsTable::configure($table);
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
            'index' => ListTeachersAccounts::route('/'),
            'create' => CreateTeachersAccount::route('/create'),
            'view' => ViewTeachersAccount::route('/{record}'),
            'edit' => EditTeachersAccount::route('/{record}/edit'),
        ];
    }

    public static function createTeacherFromUser(User $user, array $teacherData): Teacher
    {
        $teacher = Teacher::create(array_merge($teacherData, [
            'user_id' => $user->id,
        ]));

        return $teacher;
    }
}
