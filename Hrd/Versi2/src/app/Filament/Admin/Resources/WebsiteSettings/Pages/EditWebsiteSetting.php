<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\WebsiteSettings\Pages;

use App\Filament\Admin\Resources\WebsiteSettings\WebsiteSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditWebsiteSetting extends EditRecord
{
    protected static string $resource = WebsiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
