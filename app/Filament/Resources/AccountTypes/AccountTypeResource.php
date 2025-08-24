<?php

namespace App\Filament\Resources\AccountTypes;

use App\Filament\Resources\AccountTypes\Pages\CreateAccountType;
use App\Filament\Resources\AccountTypes\Pages\EditAccountType;
use App\Filament\Resources\AccountTypes\Pages\ListAccountTypes;
use App\Filament\Resources\AccountTypes\Schemas\AccountTypeForm;
use App\Filament\Resources\AccountTypes\Tables\AccountTypesTable;
use App\Models\AccountType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AccountTypeResource extends Resource
{
    protected static ?string $model = AccountType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'AccountType';

    public static function form(Schema $schema): Schema
    {
        return AccountTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccountTypesTable::configure($table);
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
            'index' => ListAccountTypes::route('/'),
            'create' => CreateAccountType::route('/create'),
            'edit' => EditAccountType::route('/{record}/edit'),
        ];
    }
}
