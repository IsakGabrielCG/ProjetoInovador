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
                    ->numeric()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Data de Vencimento')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status'),
                TextColumn::make('unit_id')
                    ->label('Unidade')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('account_type_id')
                    ->label('Tipo de Conta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_methods_id')
                    ->label('Método de Pagamento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Data de Pagamento')
                    ->date()
                    ->sortable(),
                TextColumn::make('interest_rate')
                    ->label('Taxa de Juros')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fine_amount')
                    ->label('Valor da Multa')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('amount_paid')
                    ->label('Valor Pago')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('document_number')
                    ->label('Número do Documento')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
