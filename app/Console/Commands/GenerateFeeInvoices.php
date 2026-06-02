<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Services\FeeManagement\FeeEngine;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateFeeInvoices extends Command
{
    protected $signature = 'fee:generate-invoices {--student_id=} {--start=} {--end=} {--due=}';

    protected $description = 'Generate fee invoices for one student or all active fee profiles';

    public function handle(FeeEngine $engine): int
    {
        $start = Carbon::parse($this->option('start') ?: now()->startOfMonth());
        $end = Carbon::parse($this->option('end') ?: now()->endOfMonth());
        $due = $this->option('due') ? Carbon::parse($this->option('due')) : null;

        $query = Student::query()->whereHas('studentFeeProfile', fn ($q) => $q->where('is_active', true));

        if ($studentId = $this->option('student_id')) {
            $query->whereKey($studentId);
        }

        $students = $query->get();

        if ($students->isEmpty()) {
            $this->warn('No eligible students found.');
            return self::SUCCESS;
        }

        $created = 0;
        foreach ($students as $student) {
            try {
                $invoice = $engine->createInvoiceForStudent($student, $start, $end, $due);
                $this->line("Created {$invoice->invoice_no} for student_id={$student->id}");
                $created++;
            } catch (\Throwable $e) {
                $this->error("Failed for student_id={$student->id}: {$e->getMessage()}");
            }
        }

        $this->info("Done. Generated {$created} invoice(s).");

        return self::SUCCESS;
    }
}

