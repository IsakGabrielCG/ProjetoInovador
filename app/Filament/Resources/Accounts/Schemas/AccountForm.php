<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use App\Helpers\Financeiro;


class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('status')
                    ->label('Status')
                    ->reactive()
                    ->inline() // fica lado a lado
                    ->options([
                        'paga' => 'Paga',
                        'em aberto' => 'Em aberto',
                    ])
                    ->colors([
                        'paga' => 'success',
                        'em aberto' => 'warning',
                    ])
                    ->icons([
                        'paga' => 'heroicon-o-check-circle',
                        'em aberto' => 'heroicon-o-clock',
                    ])
                    ->default('em aberto')
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 'em aberto') {
                            // limpa os campos de pagamento
                            $set('payment_methods_id', null);
                            $set('payment_date', null);
                            $set('amount_paid', null);
                        }
                    })
                    ->columnSpanFull(),

                Select::make('unit_id')
                    ->label('Unidade')
                    ->required()
                    ->relationship('unit', 'name'),

                Select::make('account_type_id')
                    ->label('Tipo de Conta')
                    ->required()
                    ->relationship('accountType', 'name'),

                TextInput::make('name')
                    ->label('Nome')
                    ->required(),

                DatePicker::make('due_date')
                    ->label('Data de Vencimento')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->required(),

                TextInput::make('document_number')
                    ->label('Número do Documento'),

                TextInput::make('description')
                    ->label('Descrição'),




                Grid::make()
                    ->schema([
                        TextInput::make('amount')
                            ->label('Valor do Documento')
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
                            ->dehydrateStateUsing(fn ($state) => $state !== null && $state !== ''
                                ? str_replace(['.', ','], ['', '.'], $state) // "1.234,56" -> "1234.56"
                                : null
                            )
                            ->afterStateUpdated(fn (Set $set, Get $get) => $set('amount_paid', Financeiro::calcularValorPago($get))),
                        TextInput::make('interest_rate')
                            ->label('Taxa de Juros')
                            ->formatStateUsing(fn ($state) => $state === null || $state === ''
                                ? null
                                : str_replace('.', ',', (string) $state)   // 1.5 -> "1,5"
                            )
                            // salva como número com ponto
                            ->dehydrateStateUsing(fn ($state) => $state !== null && $state !== ''
                                ? (string) str_replace(',', '.', $state)   // "1,5" -> "1.5"
                                : null
                            )
                            ->rule('numeric')
                            ->afterStateUpdated(fn ($set, $get) => $set('amount_paid', Financeiro::calcularValorPago($get)))
                            ->prefix('%'),

                        TextInput::make('fine_amount')
                            ->label('Valor da Multa')
                            ->formatStateUsing(fn ($state) => $state === null || $state === ''
                                ? null
                                : str_replace('.', ',', (string) $state)
                            )
                            ->dehydrateStateUsing(fn ($state) => $state !== null && $state !== ''
                                ? (string) str_replace(',', '.', $state)
                                : null
                            )
                            ->rule('numeric')
                            ->afterStateUpdated(fn ($set, $get) => $set('amount_paid', Financeiro::calcularValorPago($get)))
                            ->prefix('%'),
                    ]),

                FileUpload::make('document_path')
                    ->label('Documento'),

                Section::make('Informações de pagamento')
                    ->schema([
                        Select::make('payment_methods_id')
                            ->label('Método de Pagamento')
                            ->relationship('paymentMethod', 'name'),
                        DatePicker::make('payment_date')
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            ->native(false)
                            ->label('Data de Pagamento'),
                        TextInput::make('amount_paid')
                            ->label('Valor Pago')
                            ->disabled()
                            ->dehydrated(fn ($get) => $get('status') === 'paga')
                            // mostra sempre formatado no form
                            ->formatStateUsing(fn ($state) => $state === null || $state === ''
                                ? null
                                : number_format((float) str_replace(['.', ','], ['', '.'], $state), 2, ',', '.')
                            )
                            // salva no banco como número padrão
                            ->dehydrateStateUsing(fn ($state) => $state !== null && $state !== ''
                                ? str_replace(['.', ','], ['', '.'], $state)
                                : null
                            )
                            ->prefix('%'),
                        ])
                        ->visible(fn ($get) => $get('status') === 'paga')
                        ->collapsed(),



            ]);
    }
}
