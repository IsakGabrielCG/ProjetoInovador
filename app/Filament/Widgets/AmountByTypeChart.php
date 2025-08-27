<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AmountByTypeChart extends ChartWidget
{
    //protected ?string $heading = 'Amount By Type Chart';//geou sozinho

    protected ?string $heading = 'Gastos por Unidade'; // coloquei para mudar o nome

    private float $total = 0.0;

    protected function getData(): array
    {
            $units = Unit::query()
                ->select('units.id','units.name')
                ->withSum('accounts as total', 'amount')   // soma amount por unidade
                ->orderBy('units.name')
                ->get();

            $labels = $units->pluck('name')->toArray();
            $data   = $units->pluck('total')->map(fn($v)=> (float) ($v ?? 0))->toArray();

        $this->total = array_sum($data);


        return [
            'datasets' => [
                [
                    'label' => 'Total por Unidade',
                    'data'  => $data,
                    'backgroundColor' => [ // gerado as cores manualmente
                        '#3b82f6', // azul
                        '#22c55e', // verde
                        '#f97316', // laranja
                        '#ef4444', // vermelho
                        '#a855f7', // roxo
                        '#14b8a6', // teal
                        '#eab308', // amarelo
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'doughnut';
    }
}
