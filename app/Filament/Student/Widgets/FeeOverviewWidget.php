<?php

namespace App\Filament\Student\Widgets;

use App\Filament\Student\Resources\MyFeeInvoiceResource\MyFeeInvoiceResource;
use App\Models\FeeInvoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FeeOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return [];
        }

        $base = FeeInvoice::query()->where('student_id', $student->id);

        $outstanding = (clone $base)
            ->whereIn('status', ['issued', 'partial', 'overdue'])
            ->sum('total_due');

        $paidThisMonth = (clone $base)
            ->whereMonth('updated_at', now()->month)
            ->sum('total_paid');

        $overdueCount = (clone $base)->where('status', 'overdue')->count();

        return [
            Stat::make('Outstanding', number_format((float) $outstanding, 2))
                ->description('Current total due')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($outstanding > 0 ? 'warning' : 'success')
                ->url(MyFeeInvoiceResource::getUrl('index')),

            Stat::make('Paid This Month', number_format((float) $paidThisMonth, 2))
                ->description('All successful payments')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(MyFeeInvoiceResource::getUrl('index')),

            Stat::make('Overdue Invoices', (string) $overdueCount)
                ->description('Need attention')
                ->descriptionIcon('heroicon-m-clock')
                ->color($overdueCount > 0 ? 'danger' : 'success')
                ->url(MyFeeInvoiceResource::getUrl('index')),
        ];
    }
}

