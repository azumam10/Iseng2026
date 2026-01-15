<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-role', function () {
    return auth()->user()->hasRole('admin') ? 'Kamu Admin!' : 'Bukan Admin';
})->middleware('auth');

Route::resource('pegawai', PegawaiController::class);
