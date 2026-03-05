<?php

namespace App\Filament\Admin\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('reviewer_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('review_date')
                    ->required(),
                TextInput::make('period')
                    ->required(),
                TextInput::make('score')
                    ->required()
                    ->numeric(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                    ->default('pending')
                    ->required(),
                TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('approved_at'),
                Textarea::make('rejection_reason')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
