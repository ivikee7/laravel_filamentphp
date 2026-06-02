<?php

namespace App\Filament\Admin\Resources\FeeInvoices\Pages;

use App\Filament\Admin\Resources\FeeInvoices\FeeInvoiceResource;
use App\Models\Student;
use App\Services\FeeManagement\FeeEngine;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateFeeInvoice extends CreateRecord
{
    protected static string $resource = FeeInvoiceResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $student = Student::findOrFail($data['student_id']);

        $invoice = app(FeeEngine::class)->createInvoiceForStudent(
            $student,
            Carbon::parse($data['period_start']),
            Carbon::parse($data['period_end']),
            Carbon::parse($data['due_date'])
        );

        if (! empty($data['notes'])) {
            $invoice->notes = $data['notes'];
            $invoice->save();
        }

        return $invoice;
    }
}

