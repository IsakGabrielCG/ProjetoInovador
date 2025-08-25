<?php

namespace App\Filament\Resources\Accounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Data de Vencimento')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status'),
                TextColumn::make('unit.name')
                    ->label('Unidade')
                    ->sortable(),
                TextColumn::make('accountType.name')
                    ->label('Tipo de Conta')
                    ->sortable(),
                TextColumn::make('paymentMethod.name') //
                    ->label('Método de Pagamento')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Data de Pagamento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('interest_rate')
                    ->label('Taxa de Juros')
                    ->numeric()
                    ->sortable()
                    //->toggleable(isToggledHiddenByDefault: true)
                    ,
                TextColumn::make('fine_amount')
                    ->label('Valor da Multa')
                    ->numeric()
                    ->sortable()
                    //->toggleable(isToggledHiddenByDefault: true)
                    ,
                TextColumn::make('amount_paid')
                    ->label('Valor Pago')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('document_number')
                    ->label('Número do Documento')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable()
                    //->toggleable(isToggledHiddenByDefault: true)
                    ,
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
