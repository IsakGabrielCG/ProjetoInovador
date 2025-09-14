<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AccountsByMonthChart extends ChartWidget
{
    protected ?string $heading = 'Gastos por Mês';

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = now()->year;
    }


    protected function getFilters(): ?array
    {
        $driver = DB::getDriverName();

        //para o renderizar o filtro de anos
        if ($driver === 'sqlite') {
            // SQLite: usar strftime para pegar o ano
            $years = DB::table('accounts')
                ->selectRaw("strftime('%Y', due_date) as year")
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year', 'year')
                ->toArray();
        } else {
            // MySQL/MariaDB
            $years = DB::table('accounts')
                ->selectRaw('YEAR(due_date) as year')
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year', 'year')
                ->toArray();
        }

        if (empty($years)) {
            $years = [now()->year => now()->year];
        }

        return $years;
    }

    protected function getData(): array
    {
        $year = $this->filter ?? now()->year;
        $driver = DB::getDriverName();

        // cria os 12 meses fixos do ano
        $months = collect(range(1, 12))->map(fn ($m) => Carbon::create($year, $m, 1));

        if ($driver === 'sqlite') {
            // SQLite: usar strftime
            $rows = DB::table('accounts')
                ->selectRaw("strftime('%Y-%m', due_date) as mes, SUM(COALESCE(amount,0)) as total")
                ->whereRaw("strftime('%Y', due_date) = ?", [$year])
                ->groupBy('mes')
                ->pluck('total', 'mes');
        } else {
            // MySQL/MariaDB
            $rows = DB::table('accounts')
                ->selectRaw("DATE_FORMAT(due_date, '%Y-%m') as mes, SUM(COALESCE(amount,0)) as total")
                ->whereYear('due_date', $year)
                ->groupBy('mes')
                ->pluck('total', 'mes');
        }

        $labels = [];
        $data   = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            // cuidado: translatedFormat depende do ICU/locale do container
            $labels[] = $month->format('m/Y');
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
