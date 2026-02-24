<?php

namespace App\Filament\Widgets;

use App\Models\Archive;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ArchivesByMonthChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Arsip Diunggah per Bulan';

    // Disable default 5s polling — no need for real-time chart
    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));

        // Single GROUP BY query replacing 12 individual month queries
        $rawData = Archive::selectRaw('YEAR(created_at) as yr, MONTH(created_at) as mo, COUNT(*) as cnt')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('yr', 'mo')
            ->get()
            ->keyBy(fn ($r) => "{$r->yr}-{$r->mo}");

        $counts = $months->map(fn (Carbon $m) => (int) ($rawData["{$m->year}-{$m->month}"]->cnt ?? 0));

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Arsip',
                    'data'            => $counts->values()->all(),
                    'fill'            => true,
                    'backgroundColor' => 'rgba(205,171,47,0.15)',
                    'borderColor'     => '#CDAB2F',
                    'borderWidth'     => 2,
                    'tension'         => 0.4,
                    'pointBackgroundColor' => '#CDAB2F',
                ],
            ],
            'labels' => $months->map(fn (Carbon $m) => $m->translatedFormat('M Y'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                ],
            ],
        ];
    }
}
