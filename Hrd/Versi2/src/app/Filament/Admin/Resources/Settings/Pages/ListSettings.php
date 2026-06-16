<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Pages;

use App\Filament\Admin\Resources\Settings\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
