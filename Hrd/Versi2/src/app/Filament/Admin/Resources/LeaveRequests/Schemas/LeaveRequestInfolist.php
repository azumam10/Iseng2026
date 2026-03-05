<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeaveRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee_id')
                    ->numeric(),
                TextEntry::make('leave_type_id')
                    ->numeric(),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                TextEntry::make('reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('kabag_approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('kabag_approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('hrd_approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('hrd_approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('rejected_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('rejected_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('rejection_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
