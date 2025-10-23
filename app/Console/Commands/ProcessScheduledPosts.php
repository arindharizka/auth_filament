<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Services\InstagramService; // tambahkan service dummy

class ProcessScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled posts (dummy execution)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $service = new InstagramService(); // panggil dummy service

        $posts = Post::where('status', 'scheduled')
            ->where('scheduled_at', '<=', $now)
            ->with('account') // eager load relasi biar bisa ambil username
            ->get();

        if ($posts->isEmpty()) {
            $this->warn("⚠️ No scheduled posts found at {$now}");
            return;
        }

        foreach ($posts as $post) {
            // Jalankan dummy API kalau platform-nya Instagram
            if ($post->account->platform === 'instagram') {
                $response = $service->publish($post);
                $this->info("📸 [Instagram Dummy] {$response['message']}");
            }

            // Update status jadi posted
            $post->update(['status' => 'posted']);

            // Info lengkap di terminal
            $this->info("✅ Post {$post->id} for {$post->platform} ({$post->account->username}) marked as posted at {$now}");
        }
    }
}
