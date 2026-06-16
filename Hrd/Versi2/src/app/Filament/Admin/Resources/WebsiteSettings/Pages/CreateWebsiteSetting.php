<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\WebsiteSettings\Pages;

use App\Filament\Admin\Resources\WebsiteSettings\WebsiteSettingResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateWebsiteSetting extends CreateRecord
{
    protected static string $resource = WebsiteSettingResource::class;
}
