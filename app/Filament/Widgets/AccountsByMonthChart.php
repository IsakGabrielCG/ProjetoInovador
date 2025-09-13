<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AccountsByMonthChart extends ChartWidget
{
    protected ?string $heading = 'Gastos por Mês';

    protected function getFilters(): ?array
    {
        // Busca anos disponíveis no banco
        $years = DB::table('accounts')
            ->selectRaw('YEAR(due_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year', 'year')
            ->toArray();

        if (empty($years)) {
            $years = [now()->year => now()->year];
        }

        return $years;
    }

    protected function getData(): array
    {
        // Usa o filtro selecionado ou o ano atual como padrão
        $year = $this->filter ?? now()->year;

        // Cria os 12 meses fixos do ano escolhido
        $months = collect(range(1, 12))->map(fn ($m) => Carbon::create($year, $m, 1));

        // Busca dados do banco filtrados pelo ano
        $rows = DB::table('accounts')
            ->selectRaw("DATE_FORMAT(due_date, '%Y-%m') as mes, SUM(COALESCE(amount,0)) as total")
            ->whereYear('due_date', $year)
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $labels = [];
        $data   = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M/Y');
            $data[]   = (float) ($rows[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'data'  => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.4)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
