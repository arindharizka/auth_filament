<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'platform',
        'caption',
        'media_url',
        'status',
        'scheduled_at',
        'account_id',
    ];

    public function instagramMetrics()
{
    return $this->hasMany(\App\Models\InstagramMetric::class, 'post_id');
}

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function comments()
{
    return $this->hasMany(\App\Models\InstagramComment::class, 'post_id');
}

}
