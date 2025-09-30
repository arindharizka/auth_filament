<?php

namespace App\Filament\Resources\InstagramCommentResource\Pages;

use App\Filament\Resources\InstagramCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstagramComment extends EditRecord
{
    protected static string $resource = InstagramCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
