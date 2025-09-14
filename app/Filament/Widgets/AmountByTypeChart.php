<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AmountByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Gastos por Unidade';

    private float $total = 0.0;

    protected function getFilters(): ?array
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $months = DB::table('accounts')
                ->selectRaw("DISTINCT strftime('%Y-%m', due_date) as ym")
                ->orderByDesc('ym')
                ->pluck('ym')
                ->toArray();
        } else {
            $months = DB::table('accounts')
                ->selectRaw("DISTINCT DATE_FORMAT(due_date, '%Y-%m') as ym")
                ->orderByDesc('ym')
                ->pluck('ym')
                ->toArray();
        }

        if (empty($months)) {
            $months = [now()->format('Y-m')];
        }

        $options = [];
        foreach ($months as $ym) {
            $c = Carbon::createFromFormat('Y-m', $ym)->startOfMonth();
            $options[$ym] = $c->format('m/Y'); // usei format p/ evitar locale quebrado no Render
        }

        return $options;
    }

    protected function getData(): array
    {
        $driver = DB::getDriverName();
        $selectedYm = $this->filter;

        if (!$selectedYm) {
            if ($driver === 'sqlite') {
                $selectedYm = DB::table('accounts')
                    ->selectRaw("strftime('%Y-%m', due_date) as ym")
                    ->orderByDesc('ym')
                    ->limit(1)
                    ->value('ym');
            } else {
                $selectedYm = DB::table('accounts')
                    ->selectRaw("DATE_FORMAT(due_date, '%Y-%m') as ym")
                    ->orderByDesc('ym')
                    ->limit(1)
                    ->value('ym');
            }

            $selectedYm = $selectedYm ?? now()->format('Y-m');
        }

        $ref   = Carbon::createFromFormat('Y-m', $selectedYm)->startOfMonth();
        $start = $ref->copy()->startOfMonth();
        $end   = $ref->copy()->endOfMonth();

        $units = Unit::query()
            ->select('units.id', 'units.name')
            ->withSum(['accounts as total' => function ($query) use ($start, $end) {
                $query->whereBetween('due_date', [$start, $end]);
            }], 'amount')
            ->orderBy('units.name')
            ->get();

        $labels = $units->pluck('name')->toArray();
        $data   = $units->pluck('total')->map(fn ($v) => (float) ($v ?? 0))->toArray();

        $this->total = array_sum($data);

        $this->heading = 'Gastos por Unidade — ' . $ref->format('m/Y');

        return [
            'datasets' => [
                [
                    'label' => 'Total por Unidade',
                    'data'  => $data,
                    'backgroundColor' => [
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
