<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                Select::make('role')
                    ->label('Função')
                    ->options(['admin' => 'Admin', 'user' => 'User'])
                    ->default('admin')
                    ->required(),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required(),
            ]);
    }
}
