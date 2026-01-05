<?php

namespace App\Filament\Widgets;

use App\Models\InstagramMetric;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class InstagramSummary extends BaseWidget
{
    protected function getStats(): array
    {
        $now = now();

        return [
            Stat::make(
                'Likes Minggu Ini',
                InstagramMetric::whereBetween('recorded_at', [
                    $now->startOfWeek(),
                    $now->endOfWeek(),
                ])->sum('likes')
            ),

            Stat::make(
                'Comments Bulan Ini',
                InstagramMetric::whereMonth('recorded_at', $now->month)
                    ->whereYear('recorded_at', $now->year)
                    ->sum('comments')
            ),

            Stat::make(
                'Reach Bulan Ini',
                InstagramMetric::whereMonth('recorded_at', $now->month)
                    ->sum('reach')
            ),
        ];
    }
}
