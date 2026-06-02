<?php

namespace App\Filament\Student\Resources\MyFeeTransactionResource\Pages;

use App\Filament\Student\Resources\MyFeeTransactionResource\MyFeeTransactionResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class PrintMyFeeTransactionReceipt extends Page
{
    use InteractsWithRecord;

    protected static string $resource = MyFeeTransactionResource::class;

    protected string $view = 'filament.student.resources.my-fee-transaction-resource.pages.print-my-fee-transaction-receipt';

    protected static string $layout = 'layouts.print';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $studentId = auth()->user()?->student?->id;

        abort_unless($studentId && (int) $this->record->student_id === (int) $studentId, 403);

        $this->record->load(['student.user', 'invoice']);
    }
}

