<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Session;

class PrintMonthlyReport extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.print-monthly-report';

    protected static string $layout = 'layouts.print';

    public $getData;
    public $print_data;

    public function mount(): void
    {
        $getData = Session::get('print_data');

        if ($getData) {
            $this->print_data = json_decode($getData, true);
        }
    }
}
