<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ─── SECTION 1: Identitas Karyawan ────────────────────────────
                Section::make('Identitas Karyawan')
                    ->description('Informasi dasar dan identitas karyawan')
                    ->icon('heroicon-m-user-circle')
                    ->columns(2)
                    ->schema([
                        TextInput::make('id_number')
                            ->label('NIP / ID Karyawan')
                            ->placeholder('Contoh: EMP-0001')
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->required(),

                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('Nama sesuai KTP')
                            ->maxLength(255)
                            ->required(),

                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),

                        Select::make('education')
                            ->label('Pendidikan Terakhir')
                            ->options([
                                'SD'  => 'SD',
                                'SMP' => 'SMP',
                                'SMA' => 'SMA / SMK',
                                'D3'  => 'D3',
                                'S1'  => 'S1',
                                'S2'  => 'S2',
                            ])
                            ->placeholder('Pilih pendidikan')
                            ->nullable(),

                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()->subYears(17))
                            ->required(),
                    ]),

                // ─── SECTION 2: Kepegawaian ───────────────────────────────────
                Section::make('Data Kepegawaian')
                    ->description('Status, posisi, dan penempatan dalam organisasi')
                    ->icon('heroicon-m-building-office-2')
                    ->columns(2)
                    ->schema([
                        Select::make('employment_status')
                            ->label('Status Kepegawaian')
                            ->options([
                                'PKWTT'    => 'PKWTT (Tetap)',
                                'PKWT'     => 'PKWT (Kontrak)',
                                'HARIAN'   => 'Harian',
                                'DIREKTUR' => 'Direktur',
                            ])
                            ->default('PKWT')
                            ->required(),

                        DatePicker::make('hire_date')
                            ->label('Tanggal Bergabung')
                            ->displayFormat('d/m/Y')
                            ->maxDate(today())
                            ->required(),

                        Select::make('position_id')
                            ->label('Jabatan')
                            ->relationship('position', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Pilih jabatan'),

                        TextInput::make('level')
                            ->label('Level / Grade')
                            ->placeholder('Contoh: Staff, Supervisor, Manager')
                            ->nullable(),

                        Select::make('department_id')
                            ->label('Departemen')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Pilih departemen')
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('section_id', null)),

                        Select::make('section_id')
                            ->label('Seksi / Unit')
                            ->relationship('section', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Pilih seksi')
                            ->visible(fn (callable $get) => filled($get('department_id'))),

                        Select::make('supervisor_id')
                            ->label('Atasan Langsung')
                            ->relationship('supervisor', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Pilih atasan')
                            ->columnSpanFull(),
                    ]),

                // ─── SECTION 3: Performa ──────────────────────────────────────
                Section::make('Performa Karyawan')
                    ->description('Nilai dan kategori hasil penilaian kinerja terakhir')
                    ->icon('heroicon-m-chart-bar')
                    ->columns(2)
                    ->schema([
                        TextInput::make('performance_score')
                            ->label('Skor Performa')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->placeholder('0 - 100')
                            ->suffix('/ 100')
                            ->nullable(),

                        Select::make('performance_category')
                            ->label('Kategori Performa')
                            ->options([
                                'Low'  => '⚠️ Low Performer',
                                'Med'  => '📊 Medium Performer',
                                'High' => '⭐ High Performer',
                            ])
                            ->placeholder('Pilih kategori')
                            ->nullable(),
                    ]),

                // ─── SECTION 4: Akun Sistem ───────────────────────────────────
                Section::make('Akun Sistem')
                    ->description('Hubungkan karyawan dengan akun pengguna aplikasi')
                    ->icon('heroicon-m-key')
                    ->columns(1)
                    ->schema([
                        Select::make('user_id')
                            ->label('Akun Pengguna')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Pilih akun (opsional)'),
                    ]),

            ]);
    }
}