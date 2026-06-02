<?php

namespace App\Filament\Admin\Pages;

use App\Models\FeeInvoice;
use App\Models\FeeTransaction;
use Filament\Pages\Page;

class FeeReports extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 100;

    protected static ?string $title = 'Fee Reports';

    protected string $view = 'filament.admin.pages.fee-reports';

    public ?string $from_date = null;

    public ?string $to_date = null;

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->endOfMonth()->toDateString();
    }


    public function getDailyCollectionTotal(): float
    {
        return (float) FeeTransaction::query()
            ->whereDate('payment_date', now()->toDateString())
            ->where('status', 'success')
            ->sum('amount');
    }

    public function getPeriodCollectionTotal(): float
    {
        return (float) FeeTransaction::query()
            ->whereBetween('payment_date', [$this->from_date . ' 00:00:00', $this->to_date . ' 23:59:59'])
            ->where('status', 'success')
            ->sum('amount');
    }

    public function getOutstandingTotal(): float
    {
        return (float) FeeInvoice::query()
            ->whereIn('status', ['issued', 'partial', 'overdue'])
            ->sum('total_due')
            - (float) FeeInvoice::query()
                ->whereIn('status', ['issued', 'partial', 'overdue'])
                ->sum('total_paid');
    }

    public function getClassWiseOutstanding(): array
    {
        return FeeInvoice::query()
            ->with(['student.classAssignment.studentClass'])
            ->whereIn('status', ['issued', 'partial', 'overdue'])
            ->get()
            ->groupBy(fn ($invoice) => $invoice->student?->classAssignment?->studentClass?->name ?? 'Unassigned')
            ->map(function ($group) {
                $due = (float) $group->sum('total_due');
                $paid = (float) $group->sum('total_paid');

                return [
                    'due' => $due,
                    'paid' => $paid,
                    'outstanding' => max(0, $due - $paid),
                ];
            })
            ->toArray();
    }

    public function getStudentLedgerRows(): array
    {
        return FeeInvoice::query()
            ->with(['student.user'])
            ->whereBetween('created_at', [$this->from_date . ' 00:00:00', $this->to_date . ' 23:59:59'])
            ->orderByDesc('id')
            ->limit(100)
            ->get()
            ->map(function (FeeInvoice $invoice): array {
                return [
                    'invoice_no' => $invoice->invoice_no,
                    'student' => $invoice->student?->user?->name ?? 'N/A',
                    'admission_no' => $invoice->student?->admission_number ?? 'N/A',
                    'status' => $invoice->status,
                    'due' => (float) $invoice->total_due,
                    'paid' => (float) $invoice->total_paid,
                    'balance' => max(0, (float) $invoice->total_due - (float) $invoice->total_paid),
                ];
            })
            ->toArray();
    }
}

