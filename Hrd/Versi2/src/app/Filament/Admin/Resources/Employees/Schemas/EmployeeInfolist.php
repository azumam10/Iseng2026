<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;


class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ─── HEADER: Identity Card ───────────────────────────────────
                Section::make()
                    ->schema([
                        Flex::make([
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Nama Karyawan')
                                        ->size('lg')
                                        ->weight(FontWeight::Bold)
                                        ->icon('heroicon-m-user-circle')
                                        ->iconPosition(IconPosition::Before)
                                        ->columnSpanFull(),

                                    TextEntry::make('id_number')
                                        ->label('ID Karyawan')
                                        ->icon('heroicon-m-identification')
                                        ->iconPosition(IconPosition::Before)
                                        ->badge()
                                        ->color('gray'),
                                ]),

                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('employment_status')
                                        ->label('Status Kepegawaian')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'PKWTT'    => 'success',
                                            'PKWT'     => 'info',
                                            'HARIAN'   => 'warning',
                                            'DIREKTUR' => 'danger',
                                            default    => 'gray',
                                        }),

                                    TextEntry::make('gender')
                                        ->label('Jenis Kelamin')
                                        ->badge()
                                        ->formatStateUsing(fn (string $state): string => match ($state) {
                                            'L' => 'Laki-laki',
                                            'P' => 'Perempuan',
                                            default => $state,
                                        })
                                        ->color(fn (string $state): string => match ($state) {
                                            'L' => 'info',
                                            'P' => 'pink',
                                            default => 'gray',
                                        }),
                                ])->grow(false),
                        ])->from('md'),
                    ])
                    ->columnSpanFull(),

                // ─── SECTION 1: Informasi Jabatan ─────────────────────────────
                Section::make('Informasi Jabatan')
                    ->description('Posisi dan penempatan karyawan dalam organisasi')
                    ->icon('heroicon-m-building-office-2')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('position.name')
                            ->label('Jabatan')
                            ->icon('heroicon-m-briefcase')
                            ->iconPosition(IconPosition::Before)
                            ->placeholder('-')
                            ->weight(FontWeight::Medium),

                        TextEntry::make('department.name')
                            ->label('Departemen')
                            ->icon('heroicon-m-building-library')
                            ->iconPosition(IconPosition::Before)
                            ->placeholder('-'),

                        TextEntry::make('section.name')
                            ->label('Seksi / Unit')
                            ->icon('heroicon-m-squares-2x2')
                            ->iconPosition(IconPosition::Before)
                            ->placeholder('-'),

                        TextEntry::make('supervisor.name')
                            ->label('Atasan Langsung')
                            ->icon('heroicon-m-user-group')
                            ->iconPosition(IconPosition::Before)
                            ->placeholder('-'),

                        TextEntry::make('hire_date')
                            ->label('Tanggal Bergabung')
                            ->icon('heroicon-m-calendar-days')
                            ->iconPosition(IconPosition::Before)
                            ->date('d MMMM Y'),
                    ])
                    ->columnSpanFull(),

                // ─── SECTION 2: Data Pribadi ──────────────────────────────────
                Section::make('Data Pribadi')
                    ->description('Informasi personal karyawan')
                    ->icon('heroicon-m-user')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->icon('heroicon-m-cake')
                            ->iconPosition(IconPosition::Before)
                            ->date('d MMMM Y'),

                        TextEntry::make('age')
                            ->label('Usia')
                            ->icon('heroicon-m-clock')
                            ->iconPosition(IconPosition::Before)
                            ->formatStateUsing(fn ($state) => $state . ' tahun')
                            ->placeholder('-'),

                        TextEntry::make('generation')
                            ->label('Generasi')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'Gen Z'        => 'info',
                                'Milenial'     => 'success',
                                'Gen X'        => 'warning',
                                'Baby Boomers' => 'danger',
                                default        => 'gray',
                            })
                            ->placeholder('-'),

                        TextEntry::make('education')
                            ->label('Pendidikan Terakhir')
                            ->icon('heroicon-m-academic-cap')
                            ->iconPosition(IconPosition::Before)
                            ->placeholder('-'),
                    ])
                    ->columnSpanFull(),

                // ─── SECTION 3: Performa Karyawan ─────────────────────────────
                Section::make('Performa Karyawan')
                    ->description('Nilai dan kategori hasil penilaian kinerja terakhir')
                    ->icon('heroicon-m-chart-bar')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('performance_score')
                            ->label('Skor Performa')
                            ->icon('heroicon-m-star')
                            ->iconPosition(IconPosition::Before)
                            ->numeric(decimalPlaces: 2)
                            ->placeholder('-')
                            ->weight(FontWeight::Bold),

                        TextEntry::make('performance_category')
                            ->label('Kategori Performa')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'High' => 'success',
                                'Med'  => 'warning',
                                'Low'  => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'High' => '⭐ High Performer',
                                'Med'  => '📊 Medium Performer',
                                'Low'  => '⚠️ Low Performer',
                                default => '-',
                            })
                            ->placeholder('-'),
                    ])
                    ->columnSpanFull(),

                // ─── SECTION 4: Informasi Sistem ──────────────────────────────
                Section::make('Informasi Sistem')
                    ->description('Data teknis dan audit trail')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Akun Pengguna')
                            ->icon('heroicon-m-key')
                            ->iconPosition(IconPosition::Before)
                            ->placeholder('Belum terhubung'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->icon('heroicon-m-plus-circle')
                            ->iconPosition(IconPosition::Before)
                            ->dateTime('d MMM Y, HH:mm')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->icon('heroicon-m-pencil-square')
                            ->iconPosition(IconPosition::Before)
                            ->dateTime('d MMM Y, HH:mm')
                            ->placeholder('-'),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}