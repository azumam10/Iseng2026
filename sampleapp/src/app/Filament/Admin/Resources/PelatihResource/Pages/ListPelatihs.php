<?php

namespace App\Filament\Admin\Resources\PelatihResource\Pages;

use App\Filament\Admin\Resources\PelatihResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPelatihs extends ListRecords
{
    protected static string $resource = PelatihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
