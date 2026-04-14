<?php

namespace App\Filament\Admin\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // ─── Identitas ────────────────────────────────────────────────
                TextColumn::make('id_number')
                    ->label('NIP')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('NIP disalin!')
                    ->fontFamily('mono'),

                TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Medium)
                    ->description(fn ($record): string => $record->position?->name ?? '-'),

                // ─── Organisasi ───────────────────────────────────────────────
                TextColumn::make('department.name')
                    ->label('Departemen')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('section.name')
                    ->label('Seksi / Unit')
                    ->placeholder('-')
                    ->toggleable(),


                // ─── Status & Gender ──────────────────────────────────────────
                TextColumn::make('employment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PKWTT'    => 'success',
                        'PKWT'     => 'info',
                        'HARIAN'   => 'warning',
                        'DIREKTUR' => 'danger',
                        default    => 'gray',
                    }),

                TextColumn::make('gender')
                    ->label('L/P')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'pink',
                        default => 'gray',
                    })
                    ->toggleable(),

                // ─── Demografi ────────────────────────────────────────────────
                TextColumn::make('age')
                    ->label('Usia')
                    ->numeric()
                    ->sortable()
                    ->suffix(' thn')
                    ->toggleable(),

                TextColumn::make('generation')
                    ->label('Generasi')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Gen Z'        => 'info',
                        'Milenial'     => 'success',
                        'Gen X'        => 'warning',
                        'Baby Boomers' => 'danger',
                        default        => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('education')
                    ->label('Pendidikan')
                    ->placeholder('-')
                    ->toggleable(),

                // ─── Kepegawaian ──────────────────────────────────────────────
                TextColumn::make('hire_date')
                    ->label('Bergabung')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('supervisor.name')
                    ->label('Atasan')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                // ─── Performa ─────────────────────────────────────────────────
                TextColumn::make('performance_score')
                    ->label('Skor')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('performance_category')
                    ->label('Performa')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'High' => 'success',
                        'Med'  => 'warning',
                        'Low'  => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'High' => '⭐ High',
                        'Med'  => '📊 Med',
                        'Low'  => '⚠️ Low',
                        default => '-',
                    })
                    ->placeholder('-')
                    ->toggleable(),

                // ─── Audit ────────────────────────────────────────────────────
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, HH:mm')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, HH:mm')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('employment_status')
                    ->label('Status Kepegawaian')
                    ->options([
                        'PKWTT'    => 'PKWTT (Tetap)',
                        'PKWT'     => 'PKWT (Kontrak)',
                        'HARIAN'   => 'Harian',
                        'DIREKTUR' => 'Direktur',
                    ]),

                SelectFilter::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                SelectFilter::make('performance_category')
                    ->label('Kategori Performa')
                    ->options([
                        'High' => '⭐ High Performer',
                        'Med'  => '📊 Medium Performer',
                        'Low'  => '⚠️ Low Performer',
                    ]),

                SelectFilter::make('generation')
                    ->label('Generasi')
                    ->options([
                        'Gen Z'        => 'Gen Z',
                        'Milenial'     => 'Milenial',
                        'Gen X'        => 'Gen X',
                        'Baby Boomers' => 'Baby Boomers',
                    ]),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(5)

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('name')
            ->striped();
    }
}