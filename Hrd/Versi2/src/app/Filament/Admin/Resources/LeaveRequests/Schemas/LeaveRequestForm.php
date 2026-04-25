<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Schemas;

use App\Models\Employee;
use App\Models\LeaveType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔥 EMPLOYEE (SMART FILTER)
                Select::make('employee_id')
                ->label('Karyawan')
                ->relationship(
                    name: 'employee',
                    titleAttribute: 'name',
                    modifyQueryUsing: function (Builder $query) {
                        $user = auth()->user();
                        
                        // kepala bagian → hanya bawahan
                        if ($user->hasRole('kepala_bagian') && $user->employee) {
                            return $query->where('supervisor_id', $user->employee->id);
                            }
                            
                            // HRD → semua
                            return $query;
                            }
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
    
                // 🔥 LEAVE TYPE
                Select::make('leave_type_id')
                    ->label('Jenis Cuti')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // 🔥 DATE RANGE
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->afterOrEqual('start_date'),

                // 🔥 REASON
                Textarea::make('reason')
                    ->label('Alasan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}