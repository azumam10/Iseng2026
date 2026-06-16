<?php

declare(strict_types=1);

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
//     });
Route::get('/', [FrontendController::class, 'home']);
