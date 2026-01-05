<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\InstagramMetric;
use App\Services\InstagramService;

class FetchInstagramMetrics extends Command
{
    protected $signature = 'instagram:fetch-metrics {--post=*}';
    protected $description = 'Fetch (dummy) metrics for Instagram posts';

    public function handle()
    {
        $service = new InstagramService();
        $now = now(); // waktu pencatatan

        // Jika user ingin spesifik post: --post=1 --post=2
        $postIds = $this->option('post');

        $query = Post::with('account')->whereHas('account', function ($q) {
            $q->where('platform', 'instagram');
        });

        if (!empty($postIds)) {
            // Ambil hanya post tertentu
            $query->whereIn('id', $postIds);
        } else {
            // Default → ambil semua yang statusnya posted
            $query->where('status', 'posted');
        }

        $posts = $query->get();

        if ($posts->isEmpty()) {
            $this->info('No Instagram posts found to fetch metrics for.');
            return 0;
        }

        foreach ($posts as $post) {
            // Ambil metrik dummy dari service
            $metrics = $service->fetchMetrics($post);

            InstagramMetric::create([
                'post_id' => $post->id,
                'likes' => $metrics['likes'] ?? 0,
                'comments' => $metrics['comments'] ?? 0,
                'reach' => $metrics['reach'] ?? null,
                'impressions' => $metrics['impressions'] ?? null,

                // ⬇️ WAJIB agar widget chart tidak error
                'recorded_at' => $now,
            ]);

            $this->info("Fetched metrics for Post {$post->id} (Account: {$post->account->username})");
        }

        $this->info('Done.');
        return 0;
    }
}
