<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramComment extends Model
{
    protected $table = 'instagram_comments';

    protected $fillable = [
        'post_id',
        'username',
        'comment',
        'is_replied',
        'commented_at',
    ];

    protected $casts = [
        'is_replied' => 'boolean',
        'commented_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
