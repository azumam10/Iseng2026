<?php

namespace App\Filament\Admin\Resources\Positions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class PositionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Jabatan')
                    ->schema([
                        TextEntry::make('name')->label('Nama'),

                        TextEntry::make('level')
                            ->badge()
                            ->color('primary')
                            ->placeholder('-'),

                        TextEntry::make('created_at')->dateTime(),
                        TextEntry::make('updated_at')->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}