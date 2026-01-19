<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'payment_cycle_id',
        'id_number',
        'imported_name',
        'amount_payable',
        'net_payable', 
        'taxable_amount',
        'tax',
        'advance_pay',
        'productivity',
        'status',
        'invoice_number',
        'invoice_file',
        'resubmitted_invoice_file',
        'rejection_reason',
        'new_invoice_number',    // New column for aggregated invoices
        'new_invoice_file',      // New column for aggregated invoices
        'invoice_type'           // 'individual' for old implementation, 'aggregated' for new
    ];

    protected $casts = [
        'amount_payable' => 'decimal:2',
        'net_payable' => 'decimal:2', 
        'taxable_amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'advance_pay' => 'decimal:2',
        'productivity' => 'decimal:2',
        'invoice_type' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentCycle()
    {
        return $this->belongsTo(PaymentCycle::class);
    }

    public function getActiveInvoiceFile()
    {
        if ($this->invoice_type === 'individual') {
            return $this->resubmitted_invoice_file ?? $this->invoice_file;
        }
        return $this->new_invoice_file;
    }

    public function getActiveInvoiceNumber()
    {
        return $this->invoice_type === 'individual' ? $this->invoice_number : $this->new_invoice_number;
    }

    public function isAggregatedInvoice()
    {
        return $this->invoice_type === 'aggregated';
    }

    public function isIndividualInvoice()
    {
        return $this->invoice_type === 'individual' || $this->invoice_type === null; // null for backward compatibility
    }
    public function getCountyNameAttribute()
    {
        return $this->user->county->name ?? 'N/A';
    }
}