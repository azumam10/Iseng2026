<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_number')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('position_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('level')
                    ->default(null),
                TextInput::make('department_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('section_id')
                    ->numeric()
                    ->default(null),
                Select::make('employment_status')
                    ->options([
            'PKWTT' => 'P k w t t',
            'PKWT' => 'P k w t',
            'HARIAN' => 'H a r i a n',
            'DIREKTUR' => 'D i r e k t u r',
        ])
                    ->default('PKWT')
                    ->required(),
                Select::make('gender')
                    ->options(['L' => 'L', 'P' => 'P'])
                    ->required(),
                DatePicker::make('birth_date')
                    ->required(),
                TextInput::make('age')
                    ->numeric()
                    ->default(null),
                Select::make('generation')
                    ->options([
            'Gen Z' => 'Gen z',
            'Milenial' => 'Milenial',
            'Gen X' => 'Gen x',
            'Baby Boomers' => 'Baby boomers',
        ])
                    ->default(null),
                DatePicker::make('hire_date')
                    ->required(),
                TextInput::make('education')
                    ->default(null),
                TextInput::make('performance_score')
                    ->numeric()
                    ->default(null),
                Select::make('performance_category')
                    ->options(['Low' => 'Low', 'Med' => 'Med', 'High' => 'High'])
                    ->default(null),
                    Select::make('supervisor_id')
                    ->relationship('supervisor', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                    Select::make('user_id')
                    ->relationship('user', 'email') // atau 'name' jika ingin menampilkan nama user
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
