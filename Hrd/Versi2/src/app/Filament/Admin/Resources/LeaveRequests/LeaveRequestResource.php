<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\LeaveRequests;

use App\Filament\Admin\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Admin\Resources\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Admin\Resources\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Admin\Resources\LeaveRequests\Pages\ViewLeaveRequest;
use App\Filament\Admin\Resources\LeaveRequests\Schemas\LeaveRequestForm;
use App\Filament\Admin\Resources\LeaveRequests\Schemas\LeaveRequestInfolist;
use App\Filament\Admin\Resources\LeaveRequests\Tables\LeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

final class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationLabel = 'Permintaan Cuti';

    protected static ?string $modelLabel = 'Permintaan Cuti';

    protected static ?string $pluralModelLabel = 'Permintaan Cuti';

    protected static string|UnitEnum|null $navigationGroup = 'Management Cuti';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-s-chat-bubble-left-right';

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeaveRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        // 🔥 SUPER ADMIN & HRD → FULL ACCESS
        if ($user->hasAnyRole(['super_admin', 'hrd'])) {
            return parent::getEloquentQuery();
        }

        // 🔥 KEPALA BAGIAN → bawahan
        if ($user->hasRole('kepala_bagian') && $user->employee) {
            return parent::getEloquentQuery()
                ->whereHas('employee', fn ($q) => $q->where('supervisor_id', $user->employee->id)
                );
        }

        // 🔥 EMPLOYEE → diri sendiri
        if ($user->hasRole('employee') && $user->employee) {
            return parent::getEloquentQuery()
                ->where('employee_id', $user->employee->id);
        }

        // 🔥 fallback biar ga kosong total
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['kepala_bagian', 'hrd', 'super_admin']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'view' => ViewLeaveRequest::route('/{record}'),
            'edit' => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
