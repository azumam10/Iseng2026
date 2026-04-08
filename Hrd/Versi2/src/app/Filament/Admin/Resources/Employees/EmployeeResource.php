<?php

namespace App\Filament\Admin\Resources\Employees;

use App\Filament\Admin\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Admin\Resources\Employees\Pages\EditEmployee;
use App\Filament\Admin\Resources\Employees\Pages\ListEmployees;
use App\Filament\Admin\Resources\Employees\Pages\ViewEmployee;
use App\Filament\Admin\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Admin\Resources\Employees\Schemas\EmployeeInfolist;
use App\Filament\Admin\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = 'Karyawan';
    protected static ?string $pluralLabel = 'Karyawan';


    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

 
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    $user = auth()->user();

    // Super admin lihat semua
    if ($user->hasRole('super_admin')) {
        return $query;
    }

    // HRD lihat semua
    if ($user->hasRole('hrd')) {
        return $query;
    }

    // Manajer lihat semua (atau dibatasi nanti)
    if ($user->hasRole('manajer')) {
        return $query;
    }

    // Kepala bagian: lihat bawahan + dirinya sendiri
    if ($user->hasRole('kepala_bagian')) {
        $employee = $user->employee;
        if ($employee) {
            $subordinateIds = $employee->subordinates()->pluck('id');
            return $query->whereIn('id', $subordinateIds->push($employee->id));
        }
        return $query->whereRaw('0=1'); // tidak ada data
    }

    // Karyawan biasa: hanya lihat dirinya sendiri
    if ($user->hasRole('karyawan')) {
        return $query->where('user_id', $user->id);
    }

    return $query->whereRaw('0=1');
}

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'view' => ViewEmployee::route('/{record}'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
