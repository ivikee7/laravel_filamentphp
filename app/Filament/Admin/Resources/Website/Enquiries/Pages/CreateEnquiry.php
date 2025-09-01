<?php

namespace App\Filament\Admin\Resources\Website\Enquiries\Pages;

use App\Filament\Admin\Resources\Website\Enquiries\EnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnquiry extends CreateRecord
{
    protected static string $resource = EnquiryResource::class;
}
