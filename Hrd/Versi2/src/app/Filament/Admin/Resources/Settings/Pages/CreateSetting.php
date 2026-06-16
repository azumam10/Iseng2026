<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Pages;

use App\Filament\Admin\Resources\Settings\SettingResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;
}
