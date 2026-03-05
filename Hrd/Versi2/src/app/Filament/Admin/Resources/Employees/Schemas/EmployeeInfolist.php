<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_number'),
                TextEntry::make('name'),
                TextEntry::make('position_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('level')
                    ->placeholder('-'),
                TextEntry::make('department_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('section_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('employment_status')
                    ->badge(),
                TextEntry::make('gender')
                    ->badge(),
                TextEntry::make('birth_date')
                    ->date(),
                TextEntry::make('age')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('generation')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('hire_date')
                    ->date(),
                TextEntry::make('education')
                    ->placeholder('-'),
                TextEntry::make('performance_score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('performance_category')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('supervisor_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
