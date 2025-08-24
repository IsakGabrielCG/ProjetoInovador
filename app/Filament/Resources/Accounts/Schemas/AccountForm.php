<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('due_date')
                    ->required(),
                Select::make('status')
                    ->options(['paga' => 'Paga', 'em aberto' => 'Em aberto'])
                    ->default('em aberto')
                    ->required(),
                TextInput::make('unit_id')
                    ->required()
                    ->numeric(),
                TextInput::make('account_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_methods_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('payment_date'),
                TextInput::make('document_path'),
                TextInput::make('interest_rate')
                    ->numeric(),
                TextInput::make('fine_amount')
                    ->numeric(),
                TextInput::make('amount_paid')
                    ->numeric(),
                TextInput::make('document_number'),
                TextInput::make('description'),
            ]);
    }
}
