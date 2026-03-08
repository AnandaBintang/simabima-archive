<?php

namespace App\Filament\Widgets;

use App\Models\Archive;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ArchivesByMonthChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Arsip Diunggah';

    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    public ?string $filter = '30d';

    protected function getFilters(): ?array
    {
        return [
            '7d' => '7 Hari Terakhir',
            '30d' => '30 Hari Terakhir',
            '6m' => '6 Bulan Terakhir',
            '12m' => '12 Bulan Terakhir',
            '5y' => '5 Tahun Terakhir',
            '10y' => '10 Tahun Terakhir',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        return match ($filter) {
            '7d' => $this->getDailyData(7),
            '30d' => $this->getDailyData(30),
            '6m' => $this->getMonthlyData(6),
            '12m' => $this->getMonthlyData(12),
            '5y' => $this->getYearlyData(5),
            '10y' => $this->getYearlyData(10),
            default => $this->getDailyData(30),
        };
    }

    private function scopedArchiveQuery()
    {
        $query = Archive::query();
        $user = Auth::user();
        if ($user && $user->isStaff()) {
            $unitIds = $user->getAccessibleUnitIds();
            if ($unitIds !== null) {
                $query->whereIn('organization_unit_id', $unitIds);
            }
        }
        return $query;
    }

    private function getDailyData(int $days): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();

        $rawData = $this->scopedArchiveQuery()
            ->selectRaw('DATE(created_at) as dt, COUNT(*) as cnt')
            ->where('created_at', '>=', $startDate)
            ->groupBy('dt')
            ->get()
            ->keyBy('dt');

        $dates = collect(range($days - 1, 0))->map(fn ($i) => now()->subDays($i));
        $counts = $dates->map(fn (Carbon $d) => (int) ($rawData[$d->format('Y-m-d')]->cnt ?? 0));
        $labels = $dates->map(fn (Carbon $d) => $d->translatedFormat('d M'));

        return [
            'datasets' => [$this->buildDataset($counts->values()->all())],
            'labels' => $labels->all(),
        ];
    }

    private function getMonthlyData(int $months): array
    {
        $monthsList = collect(range($months - 1, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));

        $rawData = $this->scopedArchiveQuery()
            ->selectRaw('YEAR(created_at) as yr, MONTH(created_at) as mo, COUNT(*) as cnt')
            ->where('created_at', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->groupBy('yr', 'mo')
            ->get()
            ->keyBy(fn ($r) => "{$r->yr}-{$r->mo}");

        $counts = $monthsList->map(fn (Carbon $m) => (int) ($rawData["{$m->year}-{$m->month}"]->cnt ?? 0));
        $labels = $monthsList->map(fn (Carbon $m) => $m->translatedFormat('M Y'));

        return [
            'datasets' => [$this->buildDataset($counts->values()->all())],
            'labels' => $labels->all(),
        ];
    }

    private function getYearlyData(int $years): array
    {
        $yearsList = collect(range($years - 1, 0))->map(fn ($i) => now()->year - $i);

        $rawData = $this->scopedArchiveQuery()
            ->selectRaw('YEAR(created_at) as yr, COUNT(*) as cnt')
            ->where('created_at', '>=', Carbon::create(now()->year - $years + 1)->startOfYear())
            ->groupBy('yr')
            ->get()
            ->keyBy('yr');

        $counts = $yearsList->map(fn ($y) => (int) ($rawData[$y]->cnt ?? 0));
        $labels = $yearsList->map(fn ($y) => (string) $y);

        return [
            'datasets' => [$this->buildDataset($counts->values()->all())],
            'labels' => $labels->all(),
        ];
    }

    private function buildDataset(array $data): array
    {
        return [
            'label'           => 'Jumlah Arsip',
            'data'            => $data,
            'fill'            => true,
            'backgroundColor' => 'rgba(205,171,47,0.15)',
            'borderColor'     => '#CDAB2F',
            'borderWidth'     => 2,
            'tension'         => 0.4,
            'pointBackgroundColor' => '#CDAB2F',
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
