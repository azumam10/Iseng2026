<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Sections\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Section')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Section')
                            ->required()
                            ->maxLength(100),

                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
