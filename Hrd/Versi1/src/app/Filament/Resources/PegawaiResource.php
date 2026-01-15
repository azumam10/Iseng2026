<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// Import untuk fitur Export/Import
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use App\Filament\Exports\PegawaiExporter;
use App\Filament\Imports\PegawaiImporter;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $navigationGroup = 'Manajemen HR';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- SECTION 1: IDENTITAS UTAMA ---
                Section::make('Identitas Utama')
                    ->description('Informasi dasar dan foto profil pegawai.')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('pegawai-fotos')
                            ->maxSize(1024)
                            ->columnSpanFull()
                            ->alignCenter(),

                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('nip')
                                ->label('NIP')
                                ->required()
                                ->numeric()
                                ->unique(ignoreRecord: true)
                                ->maxLength(20),

                            Forms\Components\TextInput::make('nama_lengkap')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('gender')
                                ->options([
                                    'pria' => 'Pria',
                                    'perempuan' => 'Perempuan',
                                ])
                                ->native(false)
                                ->required(),

                            Forms\Components\DatePicker::make('tanggal_lahir')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y'),
                                
                            Forms\Components\Select::make('generasi')
                                ->options([
                                    'boomer' => 'Baby Boomer',
                                    'gen_x' => 'Gen X',
                                    'milenial' => 'Milenial',
                                    'gen_z' => 'Gen Z',
                                    'gen_alpha' => 'Gen Alpha',
                                ])
                                ->searchable(),
                        ]),
                    ]),

                // --- SECTION 2: KONTAK & ALAMAT ---
                Section::make('Kontak & Alamat')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->unique(ignoreRecord: true)
                                ->required(),

                            Forms\Components\TextInput::make('no_hp')
                                ->label('No. HP')
                                ->tel()
                                ->mask('0899-9999-99999') // Contoh mask input
                                ->maxLength(20),
                        ]),
                        Forms\Components\Textarea::make('alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                // --- SECTION 3: PEKERJAAN ---
                Section::make('Informasi Pekerjaan')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('departemen_id')
                                ->relationship('departemen', 'nama')
                                ->live() // Agar jabatan bisa memfilter berdasarkan ini
                                ->afterStateUpdated(fn (Forms\Set $set) => $set('jabatan_id', null))
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('jabatan_id')
                                ->relationship(
                                    name: 'jabatan', 
                                    titleAttribute: 'nama',
                                    modifyQueryUsing: fn (Builder $query, Forms\Get $get) => 
                                        $query->where('departemen_id', $get('departemen_id'))
                                )
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Forms\Get $get) => ! $get('departemen_id'))
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'cuti' => 'Cuti',
                                    'sakit' => 'Sakit',
                                    'resign' => 'Resign',
                                    'pensiun' => 'Pensiun',
                                ])
                                ->default('aktif')
                                ->required(),
                        ]),
                        
                        Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('tanggal_masuk')
                                ->label('Tanggal Masuk')
                                ->native(false)
                                ->displayFormat('d/m/Y'),

                            Forms\Components\Select::make('pendidikan')
                                ->options([
                                    'SMA' => 'SMA/SMK',
                                    'D3'  => 'D3',
                                    'S1'  => 'S1',
                                    'S2'  => 'S2',
                                    'S3'  => 'S3',
                                ]),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Fitur Header Actions untuk Import & Export
            ->headerActions([
                ExportAction::make()
                    ->exporter(PegawaiExporter::class)
                    ->label('Export Data')
                    ->columnMapping(false),
                ImportAction::make()
                    ->importer(PegawaiImporter::class)
                    ->label('Import Data'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Pegawai $record): string => $record->jabatan?->nama ?? '-'),

                Tables\Columns\TextColumn::make('departemen.nama')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'cuti', 'sakit' => 'warning',
                        'resign', 'pensiun' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Masa Kerja')
                    ->date('d M Y')
                    ->description(fn (Pegawai $record): string => $record->tanggal_masuk?->diffForHumans() ?? '-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('departemen')
                    ->relationship('departemen', 'nama'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'resign' => 'Resign',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([ // Dikelompokkan agar rapi
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}