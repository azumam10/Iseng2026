<?php

namespace App\Filament\Admin\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Departemen')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Nama departemen harus unik, contoh: "Sumber Daya Manusia"')
                    ->placeholder('Masukkan nama departemen'),

                TextInput::make('code')
                    ->label('Kode Departemen')
                    ->nullable()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->helperText('Kode opsional, jika diisi harus unik. Contoh: "HRD"')
                    ->placeholder('Contoh: HRD, FIN, IT'),
            ]);
    }
}