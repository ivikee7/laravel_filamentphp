<?php

namespace App\Filament\Student\Pages;

use App\Filament\Student\Resources\AttendanceResource\AttendanceResource;
use App\Filament\Student\Resources\MyFeeInvoiceResource\MyFeeInvoiceResource;
use App\Filament\Student\Resources\MyFeeTransactionResource\MyFeeTransactionResource;
use App\Filament\Student\Resources\MyCourseResource\MyCourseResource;
use App\Filament\Student\Resources\MyExamResource\MyExamResource;
use App\Filament\Student\Widgets\AttendanceOverviewWidget;
use App\Filament\Student\Widgets\FeeOverviewWidget;
use App\Filament\Student\Widgets\StudentOverviewWidget;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Home;

    protected static ?string $title = 'Student Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('my_courses')
                ->label('My Courses')
                ->icon(Heroicon::AcademicCap)
                ->url(MyCourseResource::getUrl('index')),
            Action::make('my_exams')
                ->label('My Exams')
                ->icon(Heroicon::ClipboardDocumentList)
                ->url(MyExamResource::getUrl('index')),
            Action::make('my_attendance')
                ->label('My Attendance')
                ->icon(Heroicon::CalendarDays)
                ->url(AttendanceResource::getUrl('index')),
            Action::make('my_fees')
                ->label('My Fees')
                ->icon(Heroicon::Banknotes)
                ->url(MyFeeInvoiceResource::getUrl('index')),
            Action::make('my_fee_transactions')
                ->label('Fee Transactions')
                ->icon(Heroicon::ReceiptPercent)
                ->url(MyFeeTransactionResource::getUrl('index')),
        ];
    }

    public function getWidgets(): array
    {
        return [
            StudentOverviewWidget::class,
            AttendanceOverviewWidget::class,
            FeeOverviewWidget::class,
        ];
    }
}
