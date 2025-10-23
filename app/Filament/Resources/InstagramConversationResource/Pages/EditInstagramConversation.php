<?php

namespace App\Filament\Resources\InstagramConversationResource\Pages;

use App\Filament\Resources\InstagramConversationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstagramConversation extends EditRecord
{
    protected static string $resource = InstagramConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
