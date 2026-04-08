<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('nik')
                    ->required()
                    ->maxLength(50),

                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('no_ktp')
                    ->nullable(),

                TextInput::make('agama')
                    ->nullable(),

                Select::make('status_karyawan')
                    ->options([
                        'PKWTT' => 'PKWTT',
                        'PKWT' => 'PKWT',
                        'HARIAN' => 'Harian',
                    ])
                    ->required(),

                Select::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),

                DatePicker::make('tanggal_lahir'),

                TextInput::make('pendidikan'),

                TextInput::make('status_pernikahan'),

                DatePicker::make('tanggal_masuk'),

                DatePicker::make('awal_kontrak'),

                DatePicker::make('akhir_kontrak'),

                Textarea::make('alamat')
                    ->columnSpanFull(),

                TextInput::make('jabatan'),

                // 🔥 RELASI DEPARTMENT
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}