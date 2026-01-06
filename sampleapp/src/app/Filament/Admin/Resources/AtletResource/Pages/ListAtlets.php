<?php

namespace App\Filament\Admin\Resources\AtletResource\Pages;

use App\Filament\Admin\Resources\AtletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAtlets extends ListRecords
{
    protected static string $resource = AtletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
