<?php

namespace Tests\Feature\FeeManagement;

use App\Models\FeeHead;
use App\Models\FeeInvoice;
use App\Models\FeeStructure;
use App\Models\FeeStructureItem;
use App\Models\Student;
use App\Models\StudentFeeProfile;
use App\Models\User;
use App\Services\FeeManagement\FeeEngine;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeeEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_invoice_and_records_payment(): void
    {
        config()->set('fee.late_fee.mode', 'flat');
        config()->set('fee.late_fee.flat_amount', 100);

        $user = User::factory()->create();
        $student = Student::create([
            'user_id' => $user->id,
            'admission_number' => 'ADM-001',
            'current_status' => 'active',
        ]);

        $tuition = FeeHead::create([
            'name' => 'Tuition Fee',
            'code' => 'TUITION',
            'default_amount' => 1000,
        ]);

        $transport = FeeHead::create([
            'name' => 'Transport Fee',
            'code' => 'TRANSPORT',
            'default_amount' => 500,
        ]);

        $structure = FeeStructure::create([
            'name' => 'Class 10 Monthly',
            'billing_cycle' => 'monthly',
            'is_active' => true,
        ]);

        FeeStructureItem::create([
            'fee_structure_id' => $structure->id,
            'fee_head_id' => $tuition->id,
            'amount' => 1000,
            'discountable' => true,
        ]);

        FeeStructureItem::create([
            'fee_structure_id' => $structure->id,
            'fee_head_id' => $transport->id,
            'amount' => 500,
            'discountable' => true,
        ]);

        StudentFeeProfile::create([
            'student_id' => $student->id,
            'fee_structure_id' => $structure->id,
            'scholarship_type' => 'percent',
            'scholarship_value' => 10,
            'is_active' => true,
        ]);

        $engine = app(FeeEngine::class);

        $invoice = $engine->createInvoiceForStudent(
            $student,
            Carbon::parse('2026-06-01'),
            Carbon::parse('2026-06-30')
        );

        $this->assertInstanceOf(FeeInvoice::class, $invoice);
        $this->assertEquals('issued', $invoice->status);
        $this->assertEquals(1500.00, (float) $invoice->sub_total);
        $this->assertEquals(150.00, (float) $invoice->discount_total);
        $this->assertEquals(1350.00, (float) $invoice->total_due);
        $this->assertCount(2, $invoice->items);

        $engine->recordPayment($invoice, 500, 'cash', ['reference' => 'CASH-1']);

        $invoice->refresh();
        $this->assertEquals('partial', $invoice->status);
        $this->assertEquals(500.00, (float) $invoice->total_paid);
    }
}

