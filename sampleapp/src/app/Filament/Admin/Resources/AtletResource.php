<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AtletResource\Pages;
use App\Filament\Admin\Resources\AtletResource\RelationManagers;
use App\Models\Atlet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AtletResource extends Resource
{
    protected static ?string $model = Atlet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('usia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_kelamin')
                    ->required(),
                Forms\Components\TextInput::make('kategori')
                    ->required(),
                Forms\Components\TextInput::make('tinggi')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('berat_badan')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('riwayat_cedera')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status_kesehatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('foto')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('usia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                Tables\Columns\TextColumn::make('kategori'),
                Tables\Columns\TextColumn::make('tinggi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('berat_badan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_kesehatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('foto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAtlets::route('/'),
            'create' => Pages\CreateAtlet::route('/create'),
            'edit' => Pages\EditAtlet::route('/{record}/edit'),
        ];
    }
}
