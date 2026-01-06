<?php

namespace App\Filament\Admin\Resources\JadwalPertandinganResource\Pages;

use App\Filament\Admin\Resources\JadwalPertandinganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPertandingans extends ListRecords
{
    protected static string $resource = JadwalPertandinganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
