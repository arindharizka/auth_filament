<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

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

        $posts = Post::where('status', 'scheduled')
            ->where('scheduled_at', '<=', $now)
            ->get();

        if ($posts->isEmpty()) {
            $this->warn("No scheduled posts found at {$now}");
            return;
        }

        foreach ($posts as $post) {
            $this->info("Found Post ID {$post->id} scheduled at {$post->scheduled_at}");
            $post->update(['status' => 'posted']);
            $this->info("✅ Post {$post->id} marked as posted at {$now}");
        }
    }
}
