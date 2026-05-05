<?php

namespace App\Filament\Admin\Resources\Positions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jabatan')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Jabatan')
                            ->required(),

                        TextInput::make('level')
                            ->label('Level')
                            ->placeholder('STAFF / MANAGER'),
                    ])
                    ->columns(2),
            ]);
    }
}