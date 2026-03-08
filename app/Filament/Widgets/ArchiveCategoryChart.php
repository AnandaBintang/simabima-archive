<?php

namespace App\Filament\Widgets;

use App\Models\Archive;
use App\Models\ArchiveCategory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ArchiveCategoryChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected ?string $heading = 'Distribusi Kategori Arsip';

    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 1;

    protected ?string $maxHeight = '300px';

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

    protected function getData(): array
    {
        $data = $this->scopedArchiveQuery()
            ->selectRaw('archive_category_id, COUNT(*) as cnt')
            ->groupBy('archive_category_id')
            ->get()
            ->keyBy('archive_category_id');

        // Show all categories, even those with 0 archives
        $categories = ArchiveCategory::orderBy('name')->get();

        if ($categories->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#e5e7eb'],
                        'borderWidth' => 0,
                    ],
                ],
                'labels' => ['Belum ada kategori'],
            ];
        }

        $colors = [
            '#CDAB2F', '#2A3890', '#E74C3C', '#2ECC71', '#9B59B6',
            '#F39C12', '#1ABC9C', '#E67E22', '#3498DB', '#E91E63',
            '#00BCD4', '#FF5722', '#607D8B', '#795548', '#8BC34A',
        ];

        $labels = [];
        $counts = [];
        $bgColors = [];

        foreach ($categories as $i => $category) {
            $labels[] = $category->name;
            $counts[] = (int) ($data[$category->id]->cnt ?? 0);
            $bgColors[] = $colors[$i % count($colors)];
        }

        // If all counts are 0, show a placeholder so pie chart still renders
        if (array_sum($counts) === 0) {
            return [
                'datasets' => [
                    [
                        'data' => array_fill(0, count($categories), 1),
                        'backgroundColor' => array_map(fn ($c) => $c . '40', $bgColors),
                        'borderWidth' => 2,
                        'borderColor' => '#ffffff',
                    ],
                ],
                'labels' => array_map(fn ($l) => $l . ' (0)', $labels),
            ];
        }

        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => $bgColors,
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 15,
                        'usePointStyle' => true,
                    ],
                ],
            ],
        ];
    }
}
