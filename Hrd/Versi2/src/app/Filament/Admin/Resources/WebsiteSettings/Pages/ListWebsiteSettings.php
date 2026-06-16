<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\WebsiteSettings\Pages;

use App\Filament\Admin\Resources\WebsiteSettings\WebsiteSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListWebsiteSettings extends ListRecords
{
    protected static string $resource = WebsiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
