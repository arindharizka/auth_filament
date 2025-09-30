<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramComment extends Model
{
    protected $fillable = ['instagram_post_id', 'username', 'comment'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(InstagramPost::class, 'instagram_post_id');
    }
}
