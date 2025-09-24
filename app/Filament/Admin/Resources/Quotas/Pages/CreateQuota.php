<?php

namespace App\Filament\Admin\Resources\Quotas\Pages;

use App\Filament\Admin\Resources\Quotas\QuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuota extends CreateRecord
{
    protected static string $resource = QuotaResource::class;
}
