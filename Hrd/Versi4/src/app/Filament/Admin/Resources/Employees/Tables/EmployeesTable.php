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
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_ktp')
                    ->label('No. KTP')
                    ->searchable(),

                TextColumn::make('status_karyawan')
                    ->label('Status')
                    ->badge()
                    ->color('info'),

                TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'success' : 'danger'),

                TextColumn::make('tanggal_masuk')
                    ->label('Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('department.name')
                    ->label('Departemen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_masuk', 'desc')
            ->filters([
                // nanti bisa ditambah filter departemen dll
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