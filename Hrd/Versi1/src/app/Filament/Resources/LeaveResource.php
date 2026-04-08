<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Pengajuan Cuti';
    protected static ?string $pluralLabel = 'Cuti';

    /* =====================================================
        HITUNG TOTAL HARI CUTI OTOMATIS
    ===================================================== */
    protected static function calcDays($set, $get)
    {
        if ($get('start_date') && $get('end_date')) {

            $days = Carbon::parse($get('start_date'))
                ->diffInDays(Carbon::parse($get('end_date'))) + 1;

            $set('total_days', $days);
        }
    }

    /* =====================================================
        FORM CUTI
    ===================================================== */
    public static function form(Form $form): Form
    {
        return $form->schema([

            // Pegawai otomatis dari login
            Forms\Components\Hidden::make('pegawai_id')
                ->default(fn () => auth()->user()?->pegawai?->id)
                ->required(),

            Forms\Components\Select::make('leave_type_id')
                ->relationship('leaveType', 'name')
                ->label('Jenis Cuti')
                ->required(),

            Forms\Components\DatePicker::make('start_date')
                ->label('Tanggal Mulai')
                ->reactive()
                ->afterStateUpdated(fn ($set, $get) => self::calcDays($set, $get))
                ->required(),

            Forms\Components\DatePicker::make('end_date')
                ->label('Tanggal Selesai')
                ->reactive()
                ->afterOrEqual('start_date')
                ->afterStateUpdated(fn ($set, $get) => self::calcDays($set, $get))
                ->required(),

            Forms\Components\TextInput::make('total_days')
                ->label('Total Hari')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->required(),

            Forms\Components\FileUpload::make('document_path')
                ->label('Surat Keterangan (PDF)')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('leave-documents'),

            Forms\Components\Textarea::make('hr_note')
                ->label('Catatan HR')
                ->visible(fn () => auth()->user()?->can('approve-cuti')),
        ]);
    }

    /* =====================================================
        TABLE CUTI
    ===================================================== */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pegawai.nama_lengkap')
                    ->label('Pegawai')
                    ->searchable(),

                Tables\Columns\TextColumn::make('leaveType.name')
                    ->label('Jenis Cuti'),

                Tables\Columns\TextColumn::make('start_date')
                    ->date(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Diproses')
                    ->dateTime(),
            ])

            ->actions([

                /* ================= APPROVE ================= */
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->visible(fn (Leave $record) =>
                        $record->status === 'pending'
                        && auth()->user()->can('approve-cuti')
                    )
                    ->action(function (Leave $record) {

                        $record->update([
                            'status' => 'approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        // status pegawai otomatis cuti
                        $record->pegawai->update([
                            'status' => 'cuti',
                        ]);
                    }),

                /* ================= REJECT ================= */
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->visible(fn (Leave $record) =>
                        $record->status === 'pending'
                        && auth()->user()->can('approve-cuti')
                    )
                    ->form([
                        Forms\Components\Textarea::make('hr_note')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Leave $record, array $data) {

                        $record->update([
                            'status' => 'rejected',
                            'hr_note' => $data['hr_note'],
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    }),

                /* ================= CANCEL ================= */
                Tables\Actions\Action::make('cancel')
                    ->label('Batalkan')
                    ->color('warning')
                    ->visible(fn (Leave $record) =>
                        $record->status === 'pending'
                        && $record->pegawai_id === auth()->user()?->pegawai?->id
                    )
                    ->action(function (Leave $record) {

                        $record->update([
                            'status' => 'rejected',
                            'cancelled_at' => now(),
                        ]);
                    }),
            ]);
    }

    /* =====================================================
        SEMUA USER BOLEH LIHAT MENU CUTI
    ===================================================== */
    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    /* =====================================================
        FILTER DATA BERDASARKAN ROLE
    ===================================================== */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->whereNull('cancelled_at');

        // karyawan hanya lihat cutinya sendiri
        if (auth()->user()->hasRole('karyawan')) {
            $query->where(
                'pegawai_id',
                auth()->user()->pegawai->id
            );
        }

        return $query;
    }

    /* =====================================================
        PAGES
    ===================================================== */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}