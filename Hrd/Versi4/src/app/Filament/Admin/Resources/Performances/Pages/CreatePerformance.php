<?php

namespace App\Filament\Admin\Resources\Performances\Pages;

use App\Filament\Admin\Resources\Performances\PerformanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePerformance extends CreateRecord
{
    protected static string $resource = PerformanceResource::class;
}
