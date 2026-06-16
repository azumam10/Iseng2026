<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'company_tagline',
        'company_description',

        'hero_title',
        'hero_subtitle',

        'logo',
        'hero_image',

        'address',
        'phone',
        'email',

        'youtube',
        'instagram',
        'facebook',
    ];
}
