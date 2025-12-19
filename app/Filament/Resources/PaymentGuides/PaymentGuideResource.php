<?php

namespace App\Filament\Resources\PaymentGuides;

use App\Filament\Resources\PaymentGuides\Pages\CreatePaymentGuide;
use App\Filament\Resources\PaymentGuides\Pages\EditPaymentGuide;
use App\Filament\Resources\PaymentGuides\Pages\ListPaymentGuides;
use App\Filament\Resources\PaymentGuides\Pages\ViewPaymentGuide;
use App\Filament\Resources\PaymentGuides\Schemas\PaymentGuideForm;
use App\Filament\Resources\PaymentGuides\Schemas\PaymentGuideInfolist;
use App\Filament\Resources\PaymentGuides\Tables\PaymentGuidesTable;
use App\Models\PaymentGuide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PaymentGuideResource extends Resource
{
    protected static ?string $model = PaymentGuide::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Panduan Pembayaran';

    protected static ?string $navigationLabel = 'Panduan Pembayaran';

    protected static string|UnitEnum|null $navigationGroup = 'Pembayaran';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PaymentGuideForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentGuideInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentGuidesTable::configure($table);
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
            'index' => ListPaymentGuides::route('/'),
            'create' => CreatePaymentGuide::route('/create'),
            'view' => ViewPaymentGuide::route('/{record}'),
            'edit' => EditPaymentGuide::route('/{record}/edit'),
        ];
    }
}
