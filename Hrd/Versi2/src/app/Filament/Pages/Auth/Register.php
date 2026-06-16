<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use App\Models\Employee;
use App\Models\Section;
use App\Models\User;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class Register extends BaseRegister
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
                                $s->id => $s->department->name.' - '.$s->name,
                            ])
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(),

                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->required(),
            ]);
    }

    protected function handleRegistration(array $data): User
    {
        // 1. Cari employee berdasarkan NIP + nama + seksi
        $employee = Employee::where('id_number', $data['id_number'])
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($data['name'])])
            ->where('section_id', $data['section_id'])
            ->first();

        if (! $employee) {
            throw ValidationException::withMessages([
                'id_number' => 'Data karyawan tidak ditemukan. Periksa NIP, nama, dan seksi.',
            ]);
        }

        // 2. Pastikan belum punya akun
        if ($employee->user_id) {
            throw ValidationException::withMessages([
                'id_number' => 'Karyawan ini sudah memiliki akun.',
            ]);
        }

        // 3. Buat email dari NIP, cek apakah sudah dipakai
        $email = $employee->id_number.'@sankei.com';

        if (User::where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'id_number' => 'Email untuk NIP ini sudah terdaftar. Hubungi administrator.',
            ]);
        }

        return DB::transaction(function () use ($data, $employee, $email) {
            $user = User::create([
                'name' => $employee->name,
                'email' => $email,
                'password' => Hash::make($data['password']),
            ]);

            // Assign role berdasarkan nama jabatan
            $position = mb_strtolower($employee->position->name ?? '');

            $role = match (true) {
                str_contains($position, 'kepala') => 'kepala_bagian',
                str_contains($position, 'hrd') => 'hrd',
                default => 'employee',
            };

            $user->assignRole($role);

            $employee->update(['user_id' => $user->id]);

            return $user;
        });
    }
}
