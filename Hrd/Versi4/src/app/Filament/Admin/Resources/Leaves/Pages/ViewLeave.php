<?php

namespace App\Filament\Admin\Resources\Leaves\Pages;

use App\Filament\Admin\Resources\Leaves\LeaveResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeave extends ViewRecord
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
