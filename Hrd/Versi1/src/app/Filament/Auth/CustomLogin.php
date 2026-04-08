<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;

class CustomLogin extends Login
{
    // ── Judul halaman login ──────────────────────────────────
    protected static string $view = 'filament-panels::pages.auth.login';

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Selamat Datang';
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Masuk ke HRIS';
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'Masukkan kredensial Anda untuk melanjutkan';
    }

    // ── Kustomisasi form login ────────────────────────────────
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->autocomplete()
                    ->autofocus()
                    ->extraInputAttributes(['class' => 'hris-input'])
                    ->placeholder('nama@perusahaan.com'),

                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required()
                    ->revealable()
                    ->placeholder('••••••••'),

                Checkbox::make('remember')
                    ->label('Ingat saya'),
            ])
            ->statePath('data');
    }
}