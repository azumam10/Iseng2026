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

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Ikon user group
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $navigationGroup = 'Manajemen HR';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- SECTION 1: IDENTITAS UTAMA & FOTO ---
                Section::make('Identitas Utama')
                    ->description('Informasi dasar dan foto profil pegawai.')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->avatar() // Tampilan bulat
                            ->image()
                            ->directory('pegawai-fotos') // Folder penyimpanan
                            ->columnSpanFull()
                            ->alignCenter(),

                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('nip')
                                ->label('NIP')
                                ->required()
                                ->unique(ignoreRecord: true) 
                                ->maxLength(255),

                            Forms\Components\TextInput::make('nama_lengkap')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('gender')
                                ->options([
                                    'pria' => 'Pria',
                                    'perempuan' => 'Perempuan',
                                ])
                                ->required(),

                            Forms\Components\DatePicker::make('tanggal_lahir')
                                ->required()
                                ->native(false) // Menggunakan widget kalender JS
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
                    ->collapsible() // Bisa di-minimize
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Forms\Components\TextInput::make('no_hp')
                                ->label('No. HP')
                                ->tel()
                                ->maxLength(20),
                        ]),
                        Forms\Components\Textarea::make('alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                // --- SECTION 3: PEKERJAAN & POSISI ---
                Section::make('Informasi Pekerjaan')
                    ->schema([
                        Grid::make(2)->schema([
                            // Pastikan Model Departemen memiliki kolom 'nama'
                            Forms\Components\Select::make('departemen_id')
                                ->relationship('departemen', 'nama')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('nama')->required(),
                                ]),

                            // Pastikan Model Jabatan memiliki kolom 'nama'
                            Forms\Components\Select::make('jabatan_id')
                                ->relationship('jabatan', 'nama')
                                ->searchable()
                                ->preload(),

                            Forms\Components\DatePicker::make('tanggal_masuk')
                                ->label('Tanggal Masuk')
                                ->displayFormat('d/m/Y'),
                            
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

                // --- SECTION 4: KINERJA & FILE PENDUKUNG ---
                Section::make('Kinerja & Dokumen')
                    ->columns(2)
                    ->collapsed() // Default tertutup agar form tidak terlalu panjang
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('kinerja_score')
                                    ->label('Skor Kinerja')
                                    ->numeric()
                                    ->step(0.01)
                                    ->maxValue(100),
                                
                                Forms\Components\Select::make('kinerja')
                                    ->options([
                                        'low' => 'Low',
                                        'medium' => 'Medium',
                                        'high' => 'High',
                                    ]),
                            ]),
                        
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('npwp')
                                    ->label('NPWP'),
                                    
                                Forms\Components\FileUpload::make('ktp')
                                    ->label('Foto KTP')
                                    ->image()
                                    ->directory('pegawai-ktp')
                                    ->visibility('private'), // Agar aman
                                    
                                Forms\Components\DatePicker::make('tanggal_keluar')
                                    ->label('Tanggal Resign/Keluar'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')), // Opsional jika foto kosong

                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable()
                    ->copyable(), // Bisa dikopi user

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Pegawai $record): string => $record->jabatan?->nama ?? '-'),

                Tables\Columns\TextColumn::make('departemen.nama')
                    ->sortable()
                    ->toggleable(), // User bisa hide/show kolom ini

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'cuti', 'sakit' => 'warning',
                        'resign', 'pensiun' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('kinerja')
                    ->badge()
                    ->colors([
                        'danger' => 'low',
                        'warning' => 'medium',
                        'success' => 'high',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                // Filter berdasarkan Status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'sakit' => 'Sakit',
                        'resign' => 'Resign',
                        'pensiun' => 'Pensiun',
                    ]),

                // Filter berdasarkan Departemen
                Tables\Filters\SelectFilter::make('departemen')
                    ->relationship('departemen', 'nama'),

                // Filter berdasarkan Gender
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'pria' => 'Pria',
                        'perempuan' => 'Perempuan',
                    ]),

                // Filter Data yang dihapus (Soft Deletes)
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Soft delete
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Bisa ditambahkan RelationManager di sini jika perlu
            // Contoh: RiwayatGajiRelationManager::class
        ];
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