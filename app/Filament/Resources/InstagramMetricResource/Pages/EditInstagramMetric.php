<?php

namespace App\Filament\Resources\InstagramMetricResource\Pages;

use App\Filament\Resources\InstagramMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstagramMetric extends EditRecord
{
    protected static string $resource = InstagramMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
