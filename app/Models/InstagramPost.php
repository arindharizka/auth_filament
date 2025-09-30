<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstagramPost extends Model
{
    protected $fillable = ['title', 'caption', 'posted_at'];

    public function comments(): HasMany
    {
        return $this->hasMany(InstagramComment::class);
    }
}
