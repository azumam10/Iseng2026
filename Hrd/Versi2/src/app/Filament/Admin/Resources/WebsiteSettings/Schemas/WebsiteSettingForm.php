<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\WebsiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class WebsiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Company Profile')
                    ->schema([

                        TextInput::make('company_name')
                            ->required(),

                        FileUpload::make('logo')
                            ->image()
                            ->directory('website'),

                        TextInput::make('hero_title'),

                        Textarea::make('hero_description'),

                        FileUpload::make('hero_image')
                            ->image()
                            ->directory('website'),

                        Textarea::make('about')
                            ->rows(6),

                        TextInput::make('phone'),

                        TextInput::make('email'),

                        Textarea::make('address'),

                    ]),

            ]);
    }
}
