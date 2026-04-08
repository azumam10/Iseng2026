<?php

namespace App\Filament\Admin\Resources\Performances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PerformanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('year')
                    ->required()
                    ->numeric(),
                TextInput::make('score')
                    ->required()
                    ->numeric(),
                Select::make('category')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                    ->default(null),
                TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
