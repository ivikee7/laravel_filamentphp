<?php

namespace App\Services\FeeManagement;

use App\Models\FeeInvoice;
use App\Models\FeeInvoiceItem;
use App\Models\FeeStructure;
use App\Models\FeeTransaction;
use App\Models\Student;
use App\Models\StudentFeeProfile;
use App\Services\Payments\GatewayManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FeeEngine
{
    public function __construct(protected GatewayManager $gatewayManager)
    {
    }

    public function createInvoiceForStudent(Student $student, Carbon $periodStart, Carbon $periodEnd, ?Carbon $dueDate = null): FeeInvoice
    {
        $student->loadMissing(['user', 'siblings', 'classAssignment']);

        $profile = $this->resolveActiveProfile($student);
        if (! $profile || ! $profile->structure || ! $profile->structure->is_active) {
            $studentLabel = $student->user?->name ? "{$student->user->name} (student_id={$student->id})" : "student_id={$student->id}";
            throw new \RuntimeException("Student fee profile/structure is not configured for {$studentLabel}.");
        }

        $structure = $profile->structure;
        $dueDate = $dueDate ?: Carbon::instance($periodEnd)->addDays(7);

        return DB::transaction(function () use ($student, $profile, $structure, $periodStart, $periodEnd, $dueDate) {
            $subTotal = 0.0;
            $discountTotal = 0.0;

            $invoice = FeeInvoice::create([
                'invoice_no' => $this->nextInvoiceNo(),
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'sub_total' => 0,
                'discount_total' => 0,
                'late_fee' => 0,
                'total_due' => 0,
                'total_paid' => 0,
                'status' => 'issued',
                'currency' => config('fee.currency', 'INR'),
                'settings_snapshot' => [
                    'billing_cycle' => $structure->billing_cycle,
                    'late_fee' => config('fee.late_fee'),
                ],
            ]);

            foreach ($structure->items as $item) {
                $lineBase = (float) $item->amount;
                $lineDiscount = $this->lineDiscount($lineBase, $profile, $item->discountable, $student->siblings()->count() > 0);
                $lineTotal = max(0, $lineBase - $lineDiscount);

                $subTotal += $lineBase;
                $discountTotal += $lineDiscount;

                FeeInvoiceItem::create([
                    'fee_invoice_id' => $invoice->id,
                    'fee_head_id' => $item->fee_head_id,
                    'description' => $item->feeHead?->name ?? 'Fee Item',
                    'quantity' => 1,
                    'unit_amount' => $lineBase,
                    'discount_amount' => $lineDiscount,
                    'line_total' => $lineTotal,
                    'meta' => [
                        'optional' => (bool) $item->is_optional,
                        'discountable' => (bool) $item->discountable,
                    ],
                ]);
            }

            $invoice->update([
                'sub_total' => round($subTotal, 2),
                'discount_total' => round($discountTotal, 2),
                'total_due' => round(max(0, $subTotal - $discountTotal), 2),
            ]);

            return $invoice->fresh(['items']);
        });
    }

    public function recordPayment(FeeInvoice $invoice, float $amount, string $method, array $payload = []): FeeTransaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be greater than zero.');
        }

        if ($invoice->status === 'paid') {
            throw new \RuntimeException('Invoice already fully paid.');
        }

        $gatewayResponse = $this->gatewayManager
            ->make($method)
            ->createPayment($invoice, $amount, $payload);

        $tx = FeeTransaction::create([
            'fee_invoice_id' => $invoice->id,
            'student_id' => $invoice->student_id,
            'amount' => round($amount, 2),
            'method' => $method,
            'gateway_driver' => $method,
            'status' => $gatewayResponse['status'] ?? ($payload['status'] ?? 'success'),
            'reference' => $gatewayResponse['reference'] ?? ($payload['reference'] ?? null),
            'provider_payment_id' => $gatewayResponse['provider_payment_id'] ?? null,
            'payment_date' => $payload['payment_date'] ?? now(),
            'reconciliation_status' => 'created',
            'gateway_payload' => $gatewayResponse['gateway_payload'] ?? ($payload['gateway_payload'] ?? null),
            'note' => $payload['note'] ?? null,
        ]);

        $invoice->refreshAmounts();

        return $tx;
    }

    public function applyLateFee(FeeInvoice $invoice): FeeInvoice
    {
        if ($invoice->status === 'paid' || now()->lte($invoice->due_date)) {
            return $invoice;
        }

        $lateFee = $this->calculateLateFee($invoice);

        if ($lateFee <= 0) {
            return $invoice;
        }

        $invoice->late_fee = round((float) $invoice->late_fee + $lateFee, 2);
        $invoice->total_due = round((float) $invoice->sub_total - (float) $invoice->discount_total + (float) $invoice->late_fee, 2);
        $invoice->status = $invoice->total_paid > 0 ? 'partial' : 'overdue';
        $invoice->save();

        return $invoice;
    }

    protected function lineDiscount(float $lineBase, $profile, bool $discountable, bool $hasSibling): float
    {
        if (! $discountable) {
            return 0.0;
        }

        $discount = 0.0;

        if ($profile->scholarship_type === 'percent') {
            $discount += ($lineBase * ((float) $profile->scholarship_value / 100));
        } elseif ($profile->scholarship_type === 'fixed') {
            $discount += min($lineBase, (float) $profile->scholarship_value);
        }

        if ($hasSibling && (float) $profile->sibling_discount_percent > 0) {
            $discount += ($lineBase * ((float) $profile->sibling_discount_percent / 100));
        }

        return min($lineBase, round($discount, 2));
    }

    protected function calculateLateFee(FeeInvoice $invoice): float
    {
        $mode = config('fee.late_fee.mode', 'flat');
        $days = max(1, now()->diffInDays($invoice->due_date));

        return match ($mode) {
            'daily' => (float) config('fee.late_fee.daily_amount', 0) * $days,
            'percentage' => ((float) $invoice->total_due) * ((float) config('fee.late_fee.percentage', 0) / 100),
            default => (float) config('fee.late_fee.flat_amount', 0),
        };
    }

    protected function nextInvoiceNo(): string
    {
        $date = now()->format('Ymd');
        $prefix = 'FEE-' . $date . '-';
        $last = FeeInvoice::query()
            ->where('invoice_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('invoice_no');

        $seq = 1;
        if ($last) {
            $tail = (int) substr($last, -4);
            $seq = $tail + 1;
        }

        return $prefix . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    protected function resolveActiveProfile(Student $student): ?StudentFeeProfile
    {
        $profile = $student->studentFeeProfile()
            ->with(['structure.items.feeHead'])
            ->first();

        if ($profile && $profile->is_active && $profile->structure && $profile->structure->is_active) {
            return $profile;
        }

        $classAssignment = $student->classAssignment;
        $classId = $classAssignment?->class_id;
        $academicYearId = $classAssignment?->academic_year_id;

        $structures = FeeStructure::query()
            ->where('is_active', true)
            ->when($classId, fn ($q) => $q->where(function ($sq) use ($classId) {
                $sq->where('student_class_id', $classId)->orWhereNull('student_class_id');
            }))
            ->when($academicYearId, fn ($q) => $q->where(function ($sq) use ($academicYearId) {
                $sq->where('academic_year_id', $academicYearId)->orWhereNull('academic_year_id');
            }))
            ->with(['items.feeHead'])
            ->get();

        $matchedStructure = $structures
            ->sortByDesc(fn (FeeStructure $structure): int =>
                ($structure->student_class_id ? 1 : 0) + ($structure->academic_year_id ? 1 : 0)
            )
            ->first();

        if (! $matchedStructure) {
            return null;
        }

        $resolved = StudentFeeProfile::query()->updateOrCreate(
            ['student_id' => $student->id],
            [
                'fee_structure_id' => $matchedStructure->id,
                'scholarship_type' => $profile?->scholarship_type ?? 'none',
                'scholarship_value' => $profile?->scholarship_value ?? 0,
                'sibling_discount_percent' => $profile?->sibling_discount_percent ?? 0,
                'custom_settings' => $profile?->custom_settings ?? [],
                'is_active' => true,
            ]
        );

        return $resolved->load(['structure.items.feeHead']);
    }
}

