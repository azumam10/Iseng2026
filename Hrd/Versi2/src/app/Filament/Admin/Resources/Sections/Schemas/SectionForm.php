<?php

namespace App\Filament\Admin\Resources\Sections\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('department_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
