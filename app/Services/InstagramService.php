<?php

namespace App\Services;

use App\Models\Post;
use App\Models\InstagramComment;
use Illuminate\Support\Facades\Log;

class InstagramService
{
    /**
     * Simulasikan posting ke Instagram (Dummy)
     */
    public function publish(Post $post)
    {
        Log::info("Simulated post to Instagram", [
            'account' => $post->account->username,
            'caption' => $post->caption,
            'media_url' => $post->media_url,
        ]);

        return [
            'success' => true,
            'message' => "Dummy post successfully published to Instagram.",
            'instagram_post_id' => uniqid('ig_post_'),
        ];
    }

    /**
     * Simulasikan pengambilan data metrics (Dummy)
     */
    public function fetchMetrics(Post $post)
    {
        Log::info("Fetching dummy metrics for Post ID {$post->id}");

        return [
            'likes' => rand(50, 300),
            'comments' => rand(10, 100),
            'reach' => rand(500, 5000),
            'impressions' => rand(1000, 8000),
        ];
    }

    /**
     * Simulasikan pengambilan komentar (Dummy)
     */
    public function fetchComments(Post $post): array
    {
        // Data komentar simulasi
        $comments = [
            [
                'commenter_username' => 'user_aji',
                'comment_text' => 'Keren banget kontennya!',
                'commented_at' => now()->subMinutes(10),
            ],
            [
                'commenter_username' => 'siti99',
                'comment_text' => 'Harganya berapa nih?',
                'commented_at' => now()->subMinutes(5),
            ],
        ];

        // Simpan ke DB jika ingin persist
        foreach ($comments as $c) {
            InstagramComment::create([
                'post_id' => $post->id,
                'commenter_username' => $c['commenter_username'],
                'comment_text' => $c['comment_text'],
                'commented_at' => $c['commented_at'],
            ]);
        }

        Log::info("Simulated fetch comments for post {$post->id}");

        return $comments;
    }
}
