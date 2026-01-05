<?php
// app/Filament/Widgets/InstagramMetricsWidget.php
namespace App\Filament\Widgets;

use App\Models\InstagramMetric;
use Filament\Widgets\Widget;

class InstagramMetricsWidget extends Widget
{
    protected static string $view = 'filament.widgets.instagram-metrics';

    // opsional: title widget
    protected static ?string $heading = 'Instagram: Metrik (7 hari terakhir)';

    // data siap dikirim ke view
    public function getViewData(): array
    {
        // ambil data 7 hari terakhir (modifikasi sesuai kebutuhan)
        $from = now()->subDays(6)->startOfDay();
        $to = now()->endOfDay();

        // group by date: total likes/comments/reach/impressions per day
        $rows = InstagramMetric::selectRaw('DATE(recorded_at) as date, SUM(likes) as likes, SUM(comments) as comments, SUM(reach) as reach, SUM(impressions) as impressions')
            ->whereBetween('recorded_at', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // generate labels for last 7 days
        $labels = [];
        $likes = $comments = $reach = $impressions = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M'); // label tampil
            $row = $rows->get($d);

            $likes[] = $row ? (int) $row->likes : 0;
            $comments[] = $row ? (int) $row->comments : 0;
            $reach[] = $row ? (int) $row->reach : 0;
            $impressions[] = $row ? (int) $row->impressions : 0;
        }

        return [
            'chartData' => [
                'labels' => $labels,
                'likes' => $likes,
                'comments' => $comments,
                'reach' => $reach,
                'impressions' => $impressions,
            ],
        ];
    }
}
