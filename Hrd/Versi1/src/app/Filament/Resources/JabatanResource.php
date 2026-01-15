<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JabatanResource\Pages;
use App\Models\Jabatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Jabatan';
    protected static ?string $navigationGroup = 'Manajemen HR';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Jabatan')
                    ->description('Tentukan nama jabatan dan hubungkan ke departemen terkait.')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('departemen_id')
                                ->label('Departemen')
                                ->relationship('departemen', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->native(false),

                            Forms\Components\TextInput::make('kode_jabatan')
                                ->label('Kode Jabatan')
                                ->required()
                                ->placeholder('Contoh: MGR-001')
                                ->maxLength(255),
                        ]),

                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Jabatan')
                            ->required()
                            ->placeholder('Contoh: Software Engineer')
                            ->maxLength(255),

                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('gaji_pokok_min')
                                ->label('Gaji Pokok Minimal')
                                ->numeric()
                                ->prefix('Rp')
                                ->step(1000),

                            Forms\Components\TextInput::make('gaji_pokok_max')
                                ->label('Gaji Pokok Maksimal')
                                ->numeric()
                                ->prefix('Rp')
                                ->step(1000),
                        ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Job Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_jabatan')
                    ->label('Kode')
                    ->sortable()
                    ->searchable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Jabatan')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('departemen.nama')
                    ->label('Departemen')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gaji_pokok_min')
                    ->label('Range Gaji')
                    ->money('IDR')
                    ->description(fn (Jabatan $record): string => 'Max: Rp ' . number_format($record->gaji_pokok_max, 0, ',', '.')),

                Tables\Columns\TextColumn::make('pegawais_count')
                    ->label('Jml Pegawai')
                    ->counts('pegawais')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('departemen')
                    ->relationship('departemen', 'nama'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJabatans::route('/'),
            'create' => Pages\CreateJabatan::route('/create'),
            'edit' => Pages\EditJabatan::route('/{record}/edit'),
        ];
    }
}