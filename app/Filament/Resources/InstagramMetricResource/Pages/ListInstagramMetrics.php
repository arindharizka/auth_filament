<?php

namespace App\Filament\Resources\InstagramMetricResource\Pages;

use App\Filament\Resources\InstagramMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstagramMetrics extends ListRecords
{
    protected static string $resource = InstagramMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
