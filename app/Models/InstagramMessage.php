<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramMessage extends Model
{
    protected $fillable = [
        'instagram_conversation_id',
        'sender',
        'sender_username',
        'message_text', // ✅ FIX
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(
            InstagramConversation::class,
            'instagram_conversation_id'
        );
    }
}
