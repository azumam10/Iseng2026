<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Positions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class PositionForm
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
