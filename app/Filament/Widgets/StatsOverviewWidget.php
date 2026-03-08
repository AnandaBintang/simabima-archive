<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ArchiveResource;
use App\Filament\Resources\UserResource;
use App\Models\Archive;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    // Disable default 5s polling — stats don't need real-time refresh
    protected ?string $pollingInterval = null;

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

    protected function getStats(): array
    {
        // Aggregate totals in a single query
        $agg = $this->scopedArchiveQuery()->selectRaw(
            'COUNT(*) as total,
             SUM(download_count) as total_downloads,
             SUM(CASE WHEN YEAR(created_at)=? AND MONTH(created_at)=? THEN 1 ELSE 0 END) as this_month',
            [now()->year, now()->month]
        )->first();

        $totalArchives  = (int) $agg->total;
        $thisMonth      = (int) $agg->this_month;
        $totalDownloads = (int) $agg->total_downloads;
        $totalUsers     = User::count();

        // Trend: last 6 months — ONE query replacing 6 loop queries
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $rawTrend = $this->scopedArchiveQuery()
            ->selectRaw('YEAR(created_at) as yr, MONTH(created_at) as mo, COUNT(*) as cnt')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('yr', 'mo')
            ->get()
            ->keyBy(fn ($r) => "{$r->yr}-{$r->mo}");

        $trend = collect(range(5, 0))
            ->map(fn ($i) => (int) ($rawTrend[now()->subMonths($i)->format('Y-n')]->cnt ?? 0))
            ->values()
            ->all();

        return [
            Stat::make('Total Arsip', number_format($totalArchives))
                ->description('Seluruh arsip dalam sistem')
                ->descriptionIcon('heroicon-m-archive-box')
                ->chart($trend)
                ->url(ArchiveResource::getUrl('index'))
                ->color('primary'),

            Stat::make('Total Unduhan', number_format($totalDownloads))
                ->description('Akumulasi semua unduhan')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('success'),

            Stat::make('Arsip Bulan Ini', $thisMonth)
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Total Pengguna', $totalUsers)
                ->description('Pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->url(UserResource::getUrl('index'))
                ->color('info'),
        ];
    }
}
