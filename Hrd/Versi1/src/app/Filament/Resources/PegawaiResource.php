<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PegawaiExporter;
use App\Filament\Imports\PegawaiImporter;
use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $navigationGroup = 'Manajemen HR';
    protected static ?int $navigationSort = 1;

    // -------------------------------------------------------------------------
    // Constants
    // -------------------------------------------------------------------------

    private const GENDER_OPTIONS = [
        'pria'      => 'Pria',
        'perempuan' => 'Perempuan',
    ];

    private const GENERASI_OPTIONS = [
        'boomer'    => 'Baby Boomer',
        'gen_x'     => 'Gen X',
        'milenial'  => 'Milenial',
        'gen_z'     => 'Gen Z',
        'gen_alpha' => 'Gen Alpha',
    ];

    private const STATUS_OPTIONS = [
        'aktif'   => 'Aktif',
        'cuti'    => 'Cuti',
        'sakit'   => 'Sakit',
        'resign'  => 'Resign',
        'pensiun' => 'Pensiun',
    ];

    private const PENDIDIKAN_OPTIONS = [
        'SMA' => 'SMA/SMK',
        'D3'  => 'D3',
        'S1'  => 'S1',
        'S2'  => 'S2',
        'S3'  => 'S3',
    ];

    private const STATUS_COLORS = [
        'aktif'   => 'success',
        'cuti'    => 'warning',
        'sakit'   => 'warning',
        'resign'  => 'danger',
        'pensiun' => 'danger',
    ];

    // Badge kinerja
    private const KINERJA_OPTIONS = [
        'low'    => 'Low',
        'medium' => 'Medium',
        'high'   => 'High',
    ];

    private const KINERJA_COLORS = [
        'low'    => 'danger',
        'medium' => 'warning',
        'high'   => 'success',
    ];

    // -------------------------------------------------------------------------
    // Infolist (View Page)
    // -------------------------------------------------------------------------

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            self::profilSection(),
            self::informasiPribadiSection(),
            self::kontakSection(),
            self::pekerjaanSection(),
            self::kinerjaSection(),
            self::dokumenSection(),
        ]);
    }

    private static function profilSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make()
            ->schema([
                Infolists\Components\Grid::make([
                    'default' => 1,
                    'sm'      => 4,
                ])
                ->schema([
                    ImageEntry::make('foto')
                        ->circular()
                        ->hiddenLabel()
                        ->height(120)
                        ->defaultImageUrl(fn (Pegawai $record): string =>
                            // Fallback ke UI Avatars jika foto null
                            'https://ui-avatars.com/api/?name=' . urlencode($record->nama_lengkap) . '&color=7F9CF5&background=EBF4FF'
                        )
                        ->columnSpan(1),

                    Infolists\Components\Group::make([
                        TextEntry::make('nama_lengkap')
                            ->hiddenLabel()
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(\Filament\Support\Enums\FontWeight::Bold),

                        TextEntry::make('jabatan.nama')
                            ->hiddenLabel()
                            ->color('gray')
                            ->icon('heroicon-m-briefcase')
                            ->placeholder('Jabatan belum diset'),

                        TextEntry::make('departemen.nama')
                            ->hiddenLabel()
                            ->badge()
                            ->color('primary')
                            ->placeholder('Departemen belum diset'),

                        Infolists\Components\Grid::make(['default' => 2, 'sm' => 4])
                            ->schema([
                                TextEntry::make('status')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->color(fn (string $state): string => self::statusColor($state)),

                                TextEntry::make('kinerja')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->color(fn (?string $state): string => self::kinerjaColor($state))
                                    ->formatStateUsing(fn (?string $state): string =>
                                        self::KINERJA_OPTIONS[$state] ?? '-'
                                    )
                                    ->placeholder('-'),
                            ]),
                    ])
                    ->columnSpan(3),
                ]),
            ]);
    }

    private static function informasiPribadiSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make('Informasi Pribadi')
            ->icon('heroicon-m-identification')
            ->columns(2)
            ->schema([
                TextEntry::make('nip')
                    ->label('NIP')
                    ->copyable()
                    ->copyMessage('NIP disalin!')
                    ->icon('heroicon-m-finger-print'),

                TextEntry::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (?string $state): string => self::GENDER_OPTIONS[$state] ?? '-')
                    ->icon('heroicon-m-user'),

                TextEntry::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d F Y')
                    ->icon('heroicon-m-cake')
                    ->placeholder('-'),

                TextEntry::make('generasi')
                    ->label('Generasi')
                    ->formatStateUsing(fn (?string $state): string => self::GENERASI_OPTIONS[$state] ?? '-')
                    ->badge()
                    ->color('gray'),

                TextEntry::make('pendidikan')
                    ->label('Pendidikan')
                    ->formatStateUsing(fn (?string $state): string => self::PENDIDIKAN_OPTIONS[$state] ?? '-')
                    ->icon('heroicon-m-academic-cap'),
            ]);
    }

    private static function kontakSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make('Kontak')
            ->icon('heroicon-m-phone')
            ->columns(2)
            ->schema([
                TextEntry::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->placeholder('-'),

                TextEntry::make('no_hp')
                    ->label('No. HP')
                    ->icon('heroicon-m-device-phone-mobile')
                    ->copyable()
                    ->copyMessage('No. HP disalin!')
                    ->placeholder('-'),

                TextEntry::make('alamat')
                    ->label('Alamat')
                    ->icon('heroicon-m-map-pin')
                    ->columnSpanFull()
                    ->placeholder('-'),
            ]);
    }

   private static function pekerjaanSection(): Infolists\Components\Section
{
    return Infolists\Components\Section::make('Informasi Pekerjaan')
        ->icon('heroicon-m-building-office')
        ->columns(2)
        ->schema([
            TextEntry::make('departemen.nama')
                ->label('Departemen')
                ->icon('heroicon-m-squares-2x2')
                ->placeholder('-'),

            TextEntry::make('jabatan.nama')
                ->label('Jabatan')
                ->icon('heroicon-m-briefcase')
                ->placeholder('-'),

            TextEntry::make('tanggal_masuk')
                ->label('Tanggal Masuk')
                ->date('d F Y')
                ->icon('heroicon-m-calendar-days')
                ->placeholder('-')
                ->hint(fn (Pegawai $record): string => $record->tanggal_masuk?->diffForHumans() ?? '')
                ->hintIcon('heroicon-m-clock'),

            TextEntry::make('tanggal_keluar')
                ->label('Tanggal Keluar')
                ->date('d F Y')
                ->icon('heroicon-m-calendar-x-mark')
                ->placeholder('-')
                ->visible(fn (Pegawai $record): bool => filled($record->tanggal_keluar))
                ->hint(fn (Pegawai $record): string => $record->tanggal_keluar?->diffForHumans() ?? '')
                ->hintIcon('heroicon-m-clock'),

            TextEntry::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => self::statusColor($state)),
        ]);
}

    private static function kinerjaSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make('Kinerja')
            ->icon('heroicon-m-chart-bar')
            ->columns(2)
            // Sembunyikan section ini jika kedua field null
            ->visible(fn (Pegawai $record): bool =>
                filled($record->kinerja) || filled($record->kinerja_score)
            )
            ->schema([
                TextEntry::make('kinerja')
                    ->label('Level Kinerja')
                    ->badge()
                    ->color(fn (?string $state): string => self::kinerjaColor($state))
                    ->formatStateUsing(fn (?string $state): string =>
                        self::KINERJA_OPTIONS[$state] ?? '-'
                    )
                    ->placeholder('-'),

                TextEntry::make('kinerja_score')
                    ->label('Skor Kinerja')
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' / 100')
                    ->placeholder('-'),
            ]);
    }

    private static function dokumenSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make('Dokumen')
            ->icon('heroicon-m-paper-clip')
            ->columns(2)
            ->schema([
                // KTP — preview jika gambar, link download jika PDF
                TextEntry::make('ktp')
                    ->label('KTP')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Lihat Dokumen' : '-')
                    ->url(fn (Pegawai $record): ?string => $record->ktp
                        ? asset('storage/' . $record->ktp)
                        : null
                    )
                    ->openUrlInNewTab()
                    ->color(fn (?string $state): string => $state ? 'primary' : 'gray')
                    ->icon('heroicon-m-identification-card'),

                // NPWP
                TextEntry::make('npwp')
                    ->label('NPWP')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Lihat Dokumen' : '-')
                    ->url(fn (Pegawai $record): ?string => $record->npwp
                        ? asset('storage/' . $record->npwp)
                        : null
                    )
                    ->openUrlInNewTab()
                    ->color(fn (?string $state): string => $state ? 'primary' : 'gray')
                    ->icon('heroicon-m-document-text'),
            ]);
    }

    // -------------------------------------------------------------------------
    // Form
    // -------------------------------------------------------------------------

    public static function form(Form $form): Form
    {
        return $form->schema([
            self::identitasUtamaSection(),
            self::kontakAlamatSection(),
            self::informasiPekerjaanSection(),
            self::dokumenFormSection(),  // ← section baru
        ]);
    }

    private static function identitasUtamaSection(): Section
    {
        return Section::make('Identitas Utama')
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
                        ->string()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),

                    Forms\Components\TextInput::make('nama_lengkap')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('gender')
                        ->options(self::GENDER_OPTIONS)
                        ->native(false)
                        ->required(),

                    Forms\Components\DatePicker::make('tanggal_lahir')
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                    Forms\Components\Select::make('generasi')
                        ->options(self::GENERASI_OPTIONS)
                        ->searchable(),
                ]),
            ]);
    }

    private static function kontakAlamatSection(): Section
    {
        return Section::make('Kontak & Alamat')
            ->collapsible()
            ->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->nullable(),

                    Forms\Components\TextInput::make('no_hp')
                        ->label('No. HP')
                        ->tel()
                        ->maxLength(15),

                    Forms\Components\Placeholder::make('gdrive_link_preview')
                        ->label('Google Drive')
                        ->content(fn ($record) => $record?->gdrive_folder_link
                            ? "<a href='{$record->gdrive_folder_link}' target='_blank'>Buka Folder</a>"
                            : '-'
                        )
                        ->columnSpanFull()
                        ->visible(fn ($record) => filled($record?->gdrive_folder_link)),
                ]),

                Forms\Components\Textarea::make('alamat')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    private static function informasiPekerjaanSection(): Section
    {
        return Section::make('Informasi Pekerjaan')
            ->schema([
                Grid::make(3)->schema([
                    Forms\Components\Select::make('departemen_id')
                        ->relationship('departemen', 'nama')
                        ->live()
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
                        ->options(self::STATUS_OPTIONS)
                        ->default('aktif')
                        ->live()  // ← live agar tanggal_keluar bisa reaktif
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    Forms\Components\DatePicker::make('tanggal_masuk')
                        ->label('Tanggal Masuk')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                    // tanggal_keluar: hanya muncul saat status resign/pensiun
                    Forms\Components\DatePicker::make('tanggal_keluar')
                        ->label('Tanggal Keluar')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->visible(fn (Forms\Get $get): bool =>
                            in_array($get('status'), ['resign', 'pensiun'])
                        )
                        ->requiredIf('status', ['resign', 'pensiun']),

                    Forms\Components\Select::make('pendidikan')
                        ->options(self::PENDIDIKAN_OPTIONS),
                ]),
            ]);
    }

    private static function dokumenFormSection(): Section
    {
        return Section::make('Dokumen')
            ->icon('heroicon-m-paper-clip')
            ->collapsible()
            ->schema([
                Grid::make(2)->schema([
                    Forms\Components\FileUpload::make('ktp')
                        ->label('KTP')
                        ->image()
                        ->directory('pegawai-dokumen/ktp')
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf']),

                    Forms\Components\FileUpload::make('npwp')
                        ->label('NPWP')
                        ->image()
                        ->directory('pegawai-dokumen/npwp')
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf']),
                ]),

                Grid::make(2)->schema([
                    Forms\Components\Select::make('kinerja')
                        ->label('Level Kinerja')
                        ->options(self::KINERJA_OPTIONS)
                        ->native(false),

                    Forms\Components\TextInput::make('kinerja_score')
                        ->label('Skor Kinerja')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('/ 100'),
                ]),
            ]);
    }

    // -------------------------------------------------------------------------
    // Table
    // -------------------------------------------------------------------------

    public static function table(Table $table): Table
    {
        return $table
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
                    ->circular()
                    ->defaultImageUrl(fn (Pegawai $record): string =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->nama_lengkap) . '&color=7F9CF5&background=EBF4FF'
                    ),

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
                    ->color(fn (string $state): string => self::statusColor($state)),

                // Kolom kinerja di table
                Tables\Columns\TextColumn::make('kinerja')
                    ->badge()
                    ->color(fn (?string $state): string => self::kinerjaColor($state))
                    ->formatStateUsing(fn (?string $state): string =>
                        self::KINERJA_OPTIONS[$state] ?? '-'
                    )
                    ->toggleable(isToggledHiddenByDefault: true), // hidden by default, bisa di-toggle

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Masa Kerja')
                    ->date('d M Y')
                    ->description(fn (Pegawai $record): string => $record->tanggal_masuk?->diffForHumans() ?? '-'),

                Tables\Columns\TextColumn::make('gdrive_folder_link')
                    ->label('Dokumen')
                    ->formatStateUsing(fn ($state): string => $state ? 'Buka Folder' : '-')
                    ->url(fn (Pegawai $record): ?string => $record->gdrive_folder_link)
                    ->openUrlInNewTab()
                    ->color('primary'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('departemen')
                    ->relationship('departemen', 'nama'),

                Tables\Filters\SelectFilter::make('status')
                    ->options(self::STATUS_OPTIONS), // ← pakai constant, bukan hardcode sebagian

                Tables\Filters\SelectFilter::make('kinerja')
                    ->options(self::KINERJA_OPTIONS),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private static function statusColor(string $status): string
    {
        return self::STATUS_COLORS[$status] ?? 'gray';
    }

    private static function kinerjaColor(?string $kinerja): string
    {
        return self::KINERJA_COLORS[$kinerja] ?? 'gray';
    }

    // -------------------------------------------------------------------------
    // Query & Pages
    // -------------------------------------------------------------------------

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'view'   => Pages\ViewPegawai::route('/{record}'),
            'edit'   => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}