<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class WebsiteSetting extends Model
{
    protected $fillable = [
        'company_name',
        'logo',
        'hero_title',
        'hero_description',
        'hero_image',
        'about',
        'phone',
        'email',
        'address',
    ];
}
