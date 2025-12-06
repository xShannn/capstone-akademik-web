<?php

namespace App\Filament\Resources\SppPayments;

use App\Filament\Resources\SppPayments\Pages\CreateSppPayment;
use App\Filament\Resources\SppPayments\Pages\EditSppPayment;
use App\Filament\Resources\SppPayments\Pages\ListSppPayments;
use App\Filament\Resources\SppPayments\Pages\ViewSppPayment;
use App\Filament\Resources\SppPayments\Schemas\SppPaymentForm;
use App\Filament\Resources\SppPayments\Schemas\SppPaymentInfolist;
use App\Filament\Resources\SppPayments\Tables\SppPaymentsTable;
use App\Models\SppPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SppPaymentResource extends Resource
{
    protected static ?string $model = SppPayment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel='SPP';

    protected static ?string $recordTitleAttribute = 'status';

    protected static string|UnitEnum|null $navigationGroup = 'Pembayaran';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SppPaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SppPaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SppPaymentsTable::configure($table);
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
            'index' => ListSppPayments::route('/'),
            'create' => CreateSppPayment::route('/create'),
            'view' => ViewSppPayment::route('/{record}'),
            'edit' => EditSppPayment::route('/{record}/edit'),
        ];
    }
}
