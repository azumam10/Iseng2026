<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartemenResource\Pages;
use App\Models\Departemen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DepartemenResource extends Resource
{
    protected static ?string $model = Departemen::class;

    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; // Ikon gedung kantor
    protected static ?string $navigationLabel = 'Departemen';
    protected static ?string $navigationGroup = 'Manajemen HR'; // Kelompokkan dengan Pegawai
    protected static ?int $navigationSort = 2; // Urutan setelah Data Pegawai

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Departemen')
                    ->description('Kelola data departemen atau divisi perusahaan.')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Departemen')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: Information Technology')
                            ->maxLength(255),
                            
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Keterangan')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Departemen')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Keterangan')
                    ->limit(50) // Potong teks jika terlalu panjang
                    ->wrap(),

                // Menampilkan jumlah jabatan di departemen tersebut (Opsional & Menarik)
                Tables\Columns\TextColumn::make('jabatans_count')
                    ->label('Total Jabatan')
                    ->counts('jabatans')
                    ->badge()
                    ->color('info'),

                    Tables\Columns\TextColumn::make('pegawais_count')
                    ->label('Total Pegawai')
                    ->counts('pegawais') // Pastikan relasi pegawais() ada di model Departemen
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                ->exporter(DepartemenExporter::class),
                Tables\Actions\ImportAction::make()
                ->importer(DepartemenImporter::class),
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
            'index' => Pages\ListDepartemens::route('/'),
            'create' => Pages\CreateDepartemen::route('/create'),
            'edit' => Pages\EditDepartemen::route('/{record}/edit'),
        ];
    }
}