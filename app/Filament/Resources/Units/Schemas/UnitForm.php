<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->autofocus()
                    ->unique()
                    ->required(),
                TextInput::make('cnpj')
                    ->label('CNPJ')
                    ->mask('99.999.999/9999-99')
                    ->placeholder('00.000.000/0000-00')
                    ->required(),
                TextInput::make('address')
                    ->label('Endereço'),
            ]);
    }
}
