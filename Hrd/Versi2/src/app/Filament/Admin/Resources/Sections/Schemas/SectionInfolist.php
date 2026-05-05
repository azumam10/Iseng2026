<?php

namespace App\Filament\Admin\Resources\Sections\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class SectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Section')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Section'),

                        TextEntry::make('department.name') 
                            ->label('Department')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('created_at')
                            ->dateTime('d M Y H:i'),

                        TextEntry::make('updated_at')
                            ->dateTime('d M Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}