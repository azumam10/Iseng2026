<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('no_ktp')
                    ->label('No. KTP')
                    ->maxLength(16),

                TextInput::make('agama')
                    ->label('Agama'),

                Select::make('status_karyawan')
                    ->label('Status Karyawan')
                    ->options([
                        'PKWTT' => 'PKWTT (Tetap)',
                        'PKWT'  => 'PKWT (Kontrak)',
                        'HARIAN'=> 'Harian',
                    ])
                    ->required(),

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->native(false),

                TextInput::make('pendidikan')
                    ->label('Pendidikan Terakhir'),

                TextInput::make('status_pernikahan')
                    ->label('Status Pernikahan'),

                DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->native(false)
                    ->required(),

                DatePicker::make('awal_kontrak')
                    ->label('Awal Kontrak')
                    ->native(false),

                DatePicker::make('akhir_kontrak')
                    ->label('Akhir Kontrak')
                    ->native(false),

                Textarea::make('alamat')
                    ->label('Alamat Lengkap')
                    ->columnSpanFull(),

                TextInput::make('jabatan')
                    ->label('Jabatan'),

                // 🔥 Relasi Department
                Select::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}