<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;

final class FrontendController extends Controller
{
    public function home()
    {
        $setting = Setting::first();

        return view('frontend.home', compact('setting'));
    }
}
