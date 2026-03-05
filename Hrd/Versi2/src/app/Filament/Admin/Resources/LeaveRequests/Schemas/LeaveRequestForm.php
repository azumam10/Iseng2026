<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('leave_type_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Textarea::make('reason')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'kabag_approved' => 'Kabag approved',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ])
                    ->default('pending')
                    ->required(),
                TextInput::make('kabag_approved_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('kabag_approved_at'),
                TextInput::make('hrd_approved_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('hrd_approved_at'),
                TextInput::make('rejected_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('rejected_at'),
                Textarea::make('rejection_reason')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
