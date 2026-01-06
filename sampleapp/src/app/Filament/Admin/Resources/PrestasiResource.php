<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PrestasiResource\Pages;
use App\Models\Prestasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PrestasiResource extends Resource
{
    protected static ?string $model = Prestasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Prestasi';

    protected static ?string $pluralModelLabel = 'Prestasi Atlet';

    protected static ?string $navigationGroup = 'Manajemen Atlet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('atlet_id')
                    ->relationship('atlet', 'nama')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Atlet'),

                Forms\Components\TextInput::make('kejuaraan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Select::make('medali')
                    ->options([
                        'Emas' => 'Emas',
                        'Perak' => 'Perak',
                        'Perunggu' => 'Perunggu',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('tanggal')
                    ->required(),

                Forms\Components\Textarea::make('deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('dokumentasi_foto')
                    ->image()
                    ->directory('prestasi')
                    ->imagePreviewHeight('150')
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('atlet.nama')
                    ->label('Atlet')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kejuaraan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('medali')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Emas' => 'success',
                        'Perak' => 'gray',
                        'Perunggu' => 'warning',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('dokumentasi_foto')
                    ->label('Foto')
                    ->square(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPrestasis::route('/'),
            'create' => Pages\CreatePrestasi::route('/create'),
            'edit' => Pages\EditPrestasi::route('/{record}/edit'),
        ];
    }
}
