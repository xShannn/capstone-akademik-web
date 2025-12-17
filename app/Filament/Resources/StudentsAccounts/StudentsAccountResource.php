<?php

namespace App\Filament\Resources\StudentsAccounts;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use App\Models\Student;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Models\StudentsAccount;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentsAccounts\Pages\EditStudentsAccount;
use App\Filament\Resources\StudentsAccounts\Pages\ViewStudentsAccount;
use App\Filament\Resources\StudentsAccounts\Pages\ListStudentsAccounts;
use App\Filament\Resources\StudentsAccounts\Pages\CreateStudentsAccount;
use App\Filament\Resources\StudentsAccounts\Schemas\StudentsAccountForm;
use App\Filament\Resources\StudentsAccounts\Tables\StudentsAccountsTable;
use App\Filament\Resources\StudentsAccounts\Schemas\StudentsAccountInfolist;

class StudentsAccountResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Akun';

    protected static ?string $modelLabel = 'Akun Murid';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Akun Murid';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'student')
            ->with(['student', 'student.classroom', 'student.parent']);
    }


    public static function form(Schema $schema): Schema
    {
        return StudentsAccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentsAccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsAccountsTable::configure($table);
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
            'index' => ListStudentsAccounts::route('/'),
            'create' => CreateStudentsAccount::route('/create'),
            'view' => ViewStudentsAccount::route('/{record}'),
            'edit' => EditStudentsAccount::route('/{record}/edit'),
        ];
    }

    public static function generateParentAccount(User $studentUser)
    {
        // Cek apakah siswa sudah memiliki akun orang tua
        if (!$studentUser->student || $studentUser->student->parent_user_id) {
            return null;
        }

        try {
            // Generate username untuk orang tua
            $studentName = $studentUser->student->nama_lengkap ?? $studentUser->name;
            $studentNIS = $studentUser->username;

            // Format: nama_siswa_ortu (lowercase, no spaces)
            $parentUsername = strtolower(str_replace(' ', '', $studentName)) . '_ortu';

            // Cek jika username sudah ada
            $counter = 1;
            $originalUsername = $parentUsername;

            while (User::where('username', $parentUsername)->exists()) {
                $parentUsername = $originalUsername . $counter;
                $counter++;
            }

            // Buat user untuk orang tua
            $parentUser = User::create([
                'name' => "Orang Tua dari {$studentName}",
                'username' => $parentUsername,
                'email' => $parentUsername . '@ortu.sekolah.sch.id',
                'password' => Hash::make($studentNIS), // Password = NIS anak
                'role' => 'parent',
            ]);

            // Update student dengan parent_user_id
            $studentUser->student()->update(['parent_user_id' => $parentUser->id]);

            return $parentUser;
        } catch (\Exception $e) {
            return null;
        }
    }

    // Handle form submission
    public static function createStudentWithParent(array $data): User
    {
        // 1. Create user for student
        $studentUser = User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'student',
        ]);

        // 2. Create student data
        $studentData = $data['student'];
        $studentData['user_id'] = $studentUser->id;

        $student = Student::create($studentData);

        // 3. Create parent account if requested
        if (isset($data['generate_parent_account']) && $data['generate_parent_account']) {
            self::generateParentAccount($studentUser);
        }

        return $studentUser;
    }
}
