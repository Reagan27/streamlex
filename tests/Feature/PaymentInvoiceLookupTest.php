<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Vanguard\Payment;
use Vanguard\PaymentCycle;
use Vanguard\User;
use App\Models\FieldActivity;
use App\Models\FieldActivityDocument;

class PaymentInvoiceLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_falls_back_to_latest_user_invoice_when_payment_cycle_dates_do_not_overlap(): void
    {
        $user = User::factory()->create();
        $paymentCycle = PaymentCycle::create([
            'description' => 'March 2026',
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-31',
            'is_invoicable' => true,
        ]);

        $activity = FieldActivity::create([
            'title' => 'Field activity',
            'description' => 'Invoice upload test',
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-10',
            'status' => 'approved',
            'created_by' => $user->id,
        ]);

        $document = FieldActivityDocument::create([
            'field_activity_id' => $activity->id,
            'file_name' => 'invoice.pdf',
            'file_path' => 'field_activity_documents/invoice.pdf',
            'file_type' => 'invoice',
            'file_size' => 123,
            'uploaded_by' => $user->id,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'payment_cycle_id' => $paymentCycle->id,
            'amount_payable' => 100,
            'net_payable' => 100,
            'taxable_amount' => 100,
            'tax' => 0,
            'advance_pay' => 0,
            'productivity' => 1,
            'status' => 'Pending',
        ]);

        $this->assertNotNull($payment->getFieldActivityInvoice());
        $this->assertSame($document->id, $payment->getFieldActivityInvoice()->id);
    }
}
