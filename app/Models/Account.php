<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'platform',
        'username',
        'integration_data',
    ];

    protected $casts = [
        'integration_data' => 'array',
    ];

    /**
     * Relasi: Account has many Posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
