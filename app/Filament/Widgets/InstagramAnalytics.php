<?php

namespace App\Filament\Widgets;

use App\Models\InstagramMetric;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Filament\Widgets\Widget;

class InstagramAnalytics extends Widget
{
    protected static ?string $heading = 'Instagram Analytics';
    protected static string $view = 'filament.widgets.instagram-analytics';

    public $chart;

    public function mount(LarapexChart $chart)
    {
        // Ambil total per akun: likes/comments/reach
        $rows = InstagramMetric::selectRaw('account_id, SUM(likes) as likes, SUM(comments) as comments, SUM(reach) as reach')
            ->groupBy('account_id')
            ->with('account') // agar bisa ambil username
            ->get();

        $accounts = $rows->map(fn($r) => $r->account ? $r->account->username : "account-{$r->account_id}")->toArray();
        $likes = $rows->pluck('likes')->map(fn($v) => (int)$v)->toArray();
        $comments = $rows->pluck('comments')->map(fn($v) => (int)$v)->toArray();
        $reach = $rows->pluck('reach')->map(fn($v) => (int)$v)->toArray();

        // Jika tidak ada data, beri placeholder
        if (empty($accounts)) {
            $accounts = ['No data'];
            $likes = [0];
            $comments = [0];
            $reach = [0];
        }

        $this->chart = $chart->barChart() // bisa diganti lineChart(), areaChart(), dll
            ->setTitle('Engagement per Account')
            ->setSubtitle('Total likes, comments, reach')
            ->addData('Likes', $likes)
            ->addData('Comments', $comments)
            ->addData('Reach', $reach)
            ->setXAxis($accounts);
    }
}
