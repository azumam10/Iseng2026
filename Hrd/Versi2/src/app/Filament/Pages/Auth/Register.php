<?php

namespace App\Filament\Pages\Auth;

use App\Models\Employee;
use App\Models\Section;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_number')
                    ->label('NIP')
                    ->required(),

                TextInput::make('name')
                    ->label('Nama')
                    ->required(),

                Select::make('section_id')
                    ->label('Seksi / Unit')
                    ->options(
                        Section::with('department')
                            ->get()
                            ->mapWithKeys(fn ($s) => [
                                $s->id => $s->department->name . ' - ' . $s->name,
                            ])
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->required(),

                TextInput::make('password_confirmation')
                    ->password()
                    ->required(),
            ]);
    }

    protected function handleRegistration(array $data): \App\Models\User
    {
        $employee = Employee::where('id_number', $data['id_number'])
            ->whereRaw('LOWER(name) = ?', [strtolower($data['name'])])
            ->where('section_id', $data['section_id'])
            ->first();

        if (! $employee) {
            throw ValidationException::withMessages([
                'id_number' => 'Data tidak cocok',
            ]);
        }

        if ($employee->user_id) {
            throw ValidationException::withMessages([
                'id_number' => 'Sudah punya akun',
            ]);
        }

        return DB::transaction(function () use ($data, $employee) {

            $user = \App\Models\User::create([
                'name' => $employee->name,
                'email' => $employee->id_number . '@sankei.com',
                'password' => Hash::make($data['password']),
            ]);

            $position = strtolower($employee->position->name ?? '');

            if (str_contains($position, 'kepala')) {
                $user->assignRole('kepala_bagian');
            } elseif (str_contains($position, 'hrd')) {
                $user->assignRole('hrd');
            } elseif (str_contains($position, 'manajer')) {
                $user->assignRole('manajer');   
            } else {
                $user->assignRole('employee');
            }

            $employee->update([
                'user_id' => $user->id
            ]);

            return $user;
        });
    }
}