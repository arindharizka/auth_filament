<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_username',
        'message_text',
        'sent_at',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(InstagramConversation::class);
    }
}
