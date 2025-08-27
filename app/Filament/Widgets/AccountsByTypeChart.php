<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AccountsByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Gastos por Tipo';

    protected function getData(): array
    {

        $rows = DB::table('accounts')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->selectRaw("
                account_types.name AS type,
                SUM(CASE WHEN accounts.status = 'em aberto' THEN COALESCE(accounts.amount, 0) ELSE 0 END) AS devido,
                SUM(CASE WHEN accounts.status = 'paga' THEN COALESCE(accounts.amount_paid, 0) ELSE 0 END) AS pago
            ")
            ->groupBy('account_types.name')
            ->orderBy('account_types.name')
            ->get();

        $labels   = $rows->pluck('type')->toArray();
        $devido   = $rows->pluck('devido')->map(fn ($v) => (float) $v)->toArray();
        $pago     = $rows->pluck('pago')->map(fn ($v) => (float) $v)->toArray();



        return [
            'datasets' => [
                [
                    'label' => 'Devido',
                    'data'  => $devido,
                    'barPercentage' => 0.5,
                    'categoryPercentage' => 0.7,
                    'backgroundColor' => 'rgba(255, 0, 0, 0.2)',
                    'borderColor' => 'rgba(255, 0, 0, 1)',
                ],
                [
                    'label' => 'Pago',
                    'data'  => $pago,
                    'barPercentage' => 0.5,
                    'categoryPercentage' => 0.7,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }


}
