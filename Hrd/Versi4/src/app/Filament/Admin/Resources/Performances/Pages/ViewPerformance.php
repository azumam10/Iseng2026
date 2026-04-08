<?php

namespace App\Filament\Admin\Resources\Performances\Pages;

use App\Filament\Admin\Resources\Performances\PerformanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPerformance extends ViewRecord
{
    protected static string $resource = PerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
