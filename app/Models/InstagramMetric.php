<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramMetric extends Model
{
    protected $fillable = [
        'post_id',
        'likes',
        'comments',
        'reach',
        'impressions',
        'recorded_at', // tetap ada karena kamu isi dari command
    ];

    // ✅ gunakan casts (Laravel 8 ke atas)
    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
