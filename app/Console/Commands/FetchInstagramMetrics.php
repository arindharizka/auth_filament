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
        $now = now();

        // Jika user ingin spesifik post: --post=1 --post=2
        $postIds = $this->option('post');

        $query = Post::with('account')->whereHas('account', function ($q) {
            $q->where('platform', 'instagram');
        });

        if (!empty($postIds)) {
            $query->whereIn('id', $postIds);
        } else {
            // ambil posts yang sudah posted (atau bisa semua posted)
            $query->where('status', 'posted');
        }

        $posts = $query->get();

        if ($posts->isEmpty()) {
            $this->info('No Instagram posts found to fetch metrics for.');
            return 0;
        }

        foreach ($posts as $post) {
            $metrics = $service->fetchMetrics($post); // harus mengembalikan array likes/comments/reach/impressions

            InstagramMetric::create([
                'post_id' => $post->id,
                'likes' => $metrics['likes'] ?? 0,
                'comments' => $metrics['comments'] ?? 0,
                'reach' => $metrics['reach'] ?? null,
                'impressions' => $metrics['impressions'] ?? null,
            ]);

            $this->info("Fetched metrics for Post {$post->id} (Account: {$post->account->username})");
        }

        $this->info('Done.');
        return 0;
    }
}
