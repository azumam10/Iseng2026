<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use App\Models\LeaveType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Pengajuan Cuti';
    protected static ?string $pluralLabel = 'Cuti';
    protected static function calcDays($set,$get)
{
    if($get('start_date') && $get('end_date')){
        $days = Carbon::parse($get('start_date'))
            ->diffInDays(Carbon::parse($get('end_date'))) + 1;

        $set('total_days',$days);
    }
}

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('pegawai_id')
            ->relationship('pegawai', 'nama_lengkap')
            ->searchable()
            ->required(),
            
            Forms\Components\Select::make('leave_type_id')
            ->relationship('leaveType', 'name')
            ->required(),
            
            Forms\Components\DatePicker::make('start_date')
            ->reactive()
            ->afterStateUpdated(fn ($set,$get)=>self::calcDays($set,$get))
            ->required(),
            
            Forms\Components\DatePicker::make('end_date')
            ->reactive()
            ->afterOrEqual('start_date')
            ->afterStateUpdated(fn ($set,$get)=>self::calcDays($set,$get))
            ->required(),
            
            Forms\Components\TextInput::make('total_days')
            ->numeric()
            ->disabled()
            ->dehydrated()
            ->required(),
            
            Forms\Components\FileUpload::make('document_path')
            ->acceptedFileTypes(['application/pdf'])
            ->directory('leave-documents'),
            
            Forms\Components\Textarea::make('hr_note')
            ->visible(fn()=>auth()->user()->can('approve-cuti')),
            ]);
            }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pegawai.nama_lengkap')
                    ->label('Pegawai')
                    ->searchable(),

                Tables\Columns\TextColumn::make('leaveType.name')
                    ->label('Jenis Cuti'),
                    

                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),

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
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->visible(fn (Leave $record) => $record->status === 'pending')
                    ->action(function (Leave $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);
                    }),

                    Tables\Actions\Action::make('cancel')
                    ->label('Batalkan')
                    ->color('warning')
                    ->visible(fn($record)=>$record->status==='pending')
                    ->action(function(Leave $record){
                        
                        $record->update([
                            'cancelled_at'=>now(),
                            ]);
                            }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->visible(fn (Leave $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('hr_note')
                            ->required(),
                    ])
                    ->action(function (Leave $record) {
                        
                        $record->update([
                            'status'=>'approved',
                            'approved_by'=>auth()->id(),
                            'approved_at'=>now(),
                            ]);
                            
                            $record->pegawai->update([
                                'status'=>'cuti'
                                ]);
                                }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}