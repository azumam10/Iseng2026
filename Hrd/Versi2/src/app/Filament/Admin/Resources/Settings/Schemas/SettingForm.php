<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('company_name'),

                TextInput::make('company_tagline'),

                Textarea::make('company_description')
                    ->columnSpanFull(),

                TextInput::make('hero_title'),

                Textarea::make('hero_subtitle')
                    ->columnSpanFull(),

                FileUpload::make('logo')
                    ->image()
                    ->directory('settings'),

                FileUpload::make('hero_image')
                    ->image()
                    ->directory('settings'),

                TextInput::make('address'),

                TextInput::make('phone')
                    ->tel(),

                TextInput::make('email')
                    ->email(),

                TextInput::make('youtube'),

                TextInput::make('instagram'),

                TextInput::make('facebook'),
            ]);
    }
}
