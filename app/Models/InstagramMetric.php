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
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
