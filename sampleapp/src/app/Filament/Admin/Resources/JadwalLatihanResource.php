<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JadwalLatihanResource\Pages;
use App\Filament\Admin\Resources\JadwalLatihanResource\RelationManagers;
use App\Models\JadwalLatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalLatihanResource extends Resource
{
    protected static ?string $model = JadwalLatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('atlet_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('tanggal')
                    ->required(),
                Forms\Components\TextInput::make('durasi')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jenis_latihan')
                    ->required(),
                Forms\Components\TextInput::make('pelatih_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('atlet_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('durasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_latihan'),
                Tables\Columns\TextColumn::make('pelatih_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListJadwalLatihans::route('/'),
            'create' => Pages\CreateJadwalLatihan::route('/create'),
            'edit' => Pages\EditJadwalLatihan::route('/{record}/edit'),
        ];
    }
}
