<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramConversation extends Model
{
    protected $fillable = [
        'account_id',
        'conversation_id',
        'participant_username',
        'last_message',
        'last_activity_at',
    ];

    /**
     * Generate conversation_id otomatis jika belum ada
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conversation) {
            if (empty($conversation->conversation_id)) {
                $conversation->conversation_id = uniqid('conv_');
            }
        });
    }

    /**
     * Relasi ke tabel pesan (instagram_messages)
     */
    public function messages(): HasMany
    {
        return $this->hasMany(InstagramMessage::class, 'conversation_id');
    }

    /**
     * Relasi ke akun sosial (accounts)
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
