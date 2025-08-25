<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->reactive()
                    ->mask(RawJs::make(<<<'JS'
                        $input => {
                            let x = $input.replace(/\D/g, '');
                            let intPart = x.slice(0, -2) || '0';
                            let decimalPart = x.slice(-2);
                            return parseInt(intPart).toLocaleString('pt-BR') + ',' + decimalPart;
                        }
                    JS))
                    ->prefix('R$')
                    ->reactive()
                    ->dehydrateStateUsing(function ($state) {
                        if ($state === null || $state === '') return null;

                        $raw = str_replace(['.', ','], ['', '.'], $state); // "1.234,56" -> "1234.56"

                        // opcional: inspeção rápida do que VAI para o banco
                        // dd($raw);

                        return $raw;
                    })
                    // ->afterStateUpdated(function ($state) {
                    //     if ($state) {
                    //         dd($state);
                    //     }
                    // })
                    ,
                DatePicker::make('due_date')
                    ->label('Data de Vencimento')
                    ->required(),
                Select::make('status')
                    ->options(['paga' => 'Paga', 'em aberto' => 'Em aberto'])
                    ->default('em aberto')
                    ->required(),
                Select::make('unit_id')
                    ->label('Unidade')
                    ->required()
                    ->relationship('unit', 'name'),
                Select::make('account_type_id')
                    ->label('Tipo de Conta')
                    ->required()
                    ->relationship('accountType', 'name'),
                Select::make('payment_methods_id')
                    ->label('Método de Pagamento')
                    ->relationship('paymentMethod', 'name'),
                DatePicker::make('payment_date')
                    ->label('Data de Pagamento'),
                TextInput::make('document_path')
                    ->label('Caminho do Documento'),
                TextInput::make('interest_rate')
                    ->label('Taxa de Juros')
                    ->numeric()
                    ->mask(RawJs::make(<<<'JS'
                        $input => {
                            let x = $input.replace(/\D/g, '');
                            let intPart = x.slice(0, -2) || '0';
                            let decimalPart = x.slice(-2);
                            return parseInt(intPart).toLocaleString('pt-BR') + ',' + decimalPart;
                        }
                    JS))
                    ->prefix('%'),
                TextInput::make('fine_amount')
                    ->label('Valor da Multa')
                    ->numeric()
                    ->mask(RawJs::make(<<<'JS'
                        $input => {
                            let x = $input.replace(/\D/g, '');
                            let intPart = x.slice(0, -2) || '0';
                            let decimalPart = x.slice(-2);
                            return parseInt(intPart).toLocaleString('pt-BR') + ',' + decimalPart;
                        }
                    JS))
                    ->prefix('R$'),
                TextInput::make('amount_paid')
                    ->label('Valor Pago')
                    ->numeric()
                    ->mask(RawJs::make(<<<'JS'
                        $input => {
                            let x = $input.replace(/\D/g, '');
                            let intPart = x.slice(0, -2) || '0';
                            let decimalPart = x.slice(-2);
                            return parseInt(intPart).toLocaleString('pt-BR') + ',' + decimalPart;
                        }
                    JS))
                    ->prefix('R$'),
                TextInput::make('document_number')
                    ->label('Número do Documento'),
                TextInput::make('description')
                    ->label('Descrição'),
            ]);
    }
}
