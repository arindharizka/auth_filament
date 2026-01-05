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
        'status', // ✅ WAJIB untuk CRM mini
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

            // default status kalau belum diset
            if (empty($conversation->status)) {
                $conversation->status = 'new';
            }
        });
    }

    /**
     * Relasi ke tabel pesan (instagram_messages)
     */
    public function messages(): HasMany
    {
        return $this->hasMany(InstagramMessage::class);
    }

    /**
     * Relasi ke akun sosial (accounts)
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
