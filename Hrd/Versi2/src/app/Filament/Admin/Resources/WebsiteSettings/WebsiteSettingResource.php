<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\WebsiteSettings;

use App\Filament\Admin\Resources\WebsiteSettings\Pages\CreateWebsiteSetting;
use App\Filament\Admin\Resources\WebsiteSettings\Pages\EditWebsiteSetting;
use App\Filament\Admin\Resources\WebsiteSettings\Pages\ListWebsiteSettings;
use App\Filament\Admin\Resources\WebsiteSettings\Pages\ViewWebsiteSetting;
use App\Filament\Admin\Resources\WebsiteSettings\Schemas\WebsiteSettingForm;
use App\Filament\Admin\Resources\WebsiteSettings\Schemas\WebsiteSettingInfolist;
use App\Filament\Admin\Resources\WebsiteSettings\Tables\WebsiteSettingsTable;
use App\Models\WebsiteSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class WebsiteSettingResource extends Resource
{
    protected static ?string $model = WebsiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WebsiteSettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WebsiteSettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return WebsiteSetting::count() === 0;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebsiteSettings::route('/'),
            'create' => CreateWebsiteSetting::route('/create'),
            'view' => ViewWebsiteSetting::route('/{record}'),
            'edit' => EditWebsiteSetting::route('/{record}/edit'),
        ];
    }
}
