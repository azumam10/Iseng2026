<?php

namespace App\Filament\Admin\Resources\JadwalLatihanResource\Pages;

use App\Filament\Admin\Resources\JadwalLatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalLatihan extends EditRecord
{
    protected static string $resource = JadwalLatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
