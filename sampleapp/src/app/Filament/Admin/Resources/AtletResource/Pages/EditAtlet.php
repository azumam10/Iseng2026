<?php

namespace App\Filament\Admin\Resources\AtletResource\Pages;

use App\Filament\Admin\Resources\AtletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAtlet extends EditRecord
{
    protected static string $resource = AtletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
