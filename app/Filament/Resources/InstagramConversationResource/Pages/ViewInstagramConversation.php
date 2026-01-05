<?php

namespace App\Filament\Resources\InstagramConversationResource\Pages;

use App\Filament\Resources\InstagramConversationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewInstagramConversation extends ViewRecord
{
    protected static string $resource = InstagramConversationResource::class;

    public string $message = '';

    public function sendMessage(): void
    {
        $this->record->messages()->create([
            'sender' => 'admin',
            'message' => $this->message,
        ]);

        $this->record->update([
            'last_message' => $this->message,
            'last_activity_at' => now(),
        ]);

        $this->message = '';
    }
}
