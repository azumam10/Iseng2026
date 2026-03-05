<?php

namespace App\Filament\Admin\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_number')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('position_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('level')
                    ->searchable(),
                TextColumn::make('department_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('section_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employment_status')
                    ->badge(),
                TextColumn::make('gender')
                    ->badge(),
                TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('generation')
                    ->badge(),
                TextColumn::make('hire_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('education')
                    ->searchable(),
                TextColumn::make('performance_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('performance_category')
                    ->badge(),
                TextColumn::make('supervisor_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
