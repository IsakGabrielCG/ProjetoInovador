<?php

namespace App\Filament\Resources\Accounts\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('status')
                    ->label('Status')
                    ->alignCenter()
                    ->icon(function (string $state, $record): Heroicon {
                        if (! $record->due_date) {
                            return $state === 'paga'
                                ? Heroicon::OutlinedCheckCircle
                                : Heroicon::OutlinedClock;
                        }

                        // Comparar data
                        $today = Carbon::today(config('app.timezone', 'America/Sao_Paulo'));
                        $due   = Carbon::parse($record->due_date)->startOfDay();

                        $isOverdue = $state === 'em aberto' && $due->lt($today); // < hoje = vencida

                        return match (true) {
                            $state === 'paga'                        => Heroicon::OutlinedCheckCircle,
                            $isOverdue                               => Heroicon::OutlinedExclamationCircle,
                            $state === 'em aberto' && $due->equalTo($today) => Heroicon::OutlinedCalendarDays, // vence hoje
                            default                                  => Heroicon::OutlinedClock,
                        };
                    })
                    ->color(function (string $state, $record): string {
                        if (! $record->due_date) {
                            return $state === 'paga' ? 'success' : 'warning';
                        }

                        $today = Carbon::today(config('app.timezone', 'America/Sao_Paulo'));
                        $due   = Carbon::parse($record->due_date)->startOfDay();

                        if ($state === 'paga') return 'success';
                        if ($due->lt($today))  return 'danger';   // vencida
                        if ($due->equalTo($today)) return 'info'; // vence hoje

                        return 'warning'; // em aberto e ainda dentro do prazo
                    })
                    ->tooltip(function ($record) {
                        if ($record->status === 'paga') {
                            return 'Paga';
                        }

                        if (! $record->due_date) {
                            return null;
                        }

                        $today = Carbon::today(config('app.timezone', 'America/Sao_Paulo'));
                        $due   = Carbon::parse($record->due_date)->startOfDay();

                        if ($due->lt($today)) {
                            $dias = $due->diffInDays($today);
                            return "Vencida há {$dias} dia(s)";
                        }

                        if ($due->equalTo($today)) {
                            return 'Vence hoje';
                        }

                        $dias = $today->diffInDays($due);
                        return "Vence em {$dias} dia(s)";
                    }),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('document_number')
                    ->label('N° Documento')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('due_date')
                    ->label('Data de Vencimento')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('unit.name')
                    ->label('Unidade')
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('accountType.name')
                    ->label('Tipo de Conta')
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('paymentMethod.name') //
                    ->label('Método de Pagamento')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_date')
                    ->label('Data de Pagamento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'em aberto' => 'Em Aberto',
                        'paga'      => 'Paga',
                        'cancelada' => 'Cancelada',
                    ])
                    ->label('Status'),

                SelectFilter::make('unit_id')
                    ->relationship('unit', 'name')
                    ->label('Unidade'),

                Filter::make('due_date_range')
                    ->label('Período de Vencimento')
                    ->form([
                        DatePicker::make('from')->label('De'),
                        DatePicker::make('until')->label('Até'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('due_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('due_date', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    // DeleteAction::make(), não vai ser usado por enquanto
                    ViewAction::make(),
                ])
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]), não vai ser usado por enquanto
            ]);
    }
}
