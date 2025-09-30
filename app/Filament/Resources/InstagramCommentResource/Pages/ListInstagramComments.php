<?php

namespace App\Filament\Resources\InstagramCommentResource\Pages;

use App\Filament\Resources\InstagramCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstagramComments extends ListRecords
{
    protected static string $resource = InstagramCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
