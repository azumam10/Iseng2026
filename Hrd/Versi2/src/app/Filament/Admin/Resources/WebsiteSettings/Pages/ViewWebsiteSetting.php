<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\WebsiteSettings\Pages;

use App\Filament\Admin\Resources\WebsiteSettings\WebsiteSettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewWebsiteSetting extends ViewRecord
{
    protected static string $resource = WebsiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
