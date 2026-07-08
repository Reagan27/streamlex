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

    /**
     * Get the most relevant FAM invoice for this payment.
     *
     * First try to find an invoice linked to a field activity that overlaps the
     * payment cycle. If none exists, fall back to the latest invoice uploaded by
     * the user so the payments page still shows the document the user submitted.
     */
    public function getFieldActivityInvoice()
    {
        if (!$this->user_id) {
            return null;
        }

        $invoiceQuery = \App\Models\FieldActivityDocument::whereHas('fieldActivity', function($query) {
                $query->where('created_by', $this->user_id);
            })
            ->where('file_type', 'invoice');

        if ($this->paymentCycle) {
            $invoiceQuery->whereHas('fieldActivity', function ($query) {
                $cycleStart = $this->paymentCycle->start_date;
                $cycleEnd = $this->paymentCycle->end_date;

                $query->where(function ($q) use ($cycleStart, $cycleEnd) {
                    $q->whereBetween('start_date', [$cycleStart, $cycleEnd])
                      ->orWhereBetween('end_date', [$cycleStart, $cycleEnd])
                      ->orWhere(function ($qq) use ($cycleStart, $cycleEnd) {
                          $qq->where('start_date', '<=', $cycleStart)
                             ->where('end_date', '>=', $cycleEnd);
                      });
                });
            });
        }

        $invoice = $invoiceQuery->latest('created_at')->first();

        if ($invoice) {
            return $invoice;
        }

        return \App\Models\FieldActivityDocument::whereHas('fieldActivity', function($query) {
                $query->where('created_by', $this->user_id);
            })
            ->where('file_type', 'invoice')
            ->latest('created_at')
            ->first();
    }

    public function getFieldActivityInvoiceUrl()
    {
        $invoice = $this->getFieldActivityInvoice();

        if (!$invoice) {
            return null;
        }

        return url('/api/field-activity-documents/' . $invoice->id . '/view');
    }

    /**
     * Calculate productivity from field activities within the payment cycle
     */
    public function calculateProductivityFromActivities()
    {
        if (!$this->user_id || !$this->paymentCycle) {
            return 0;
        }

        $cycle = $this->paymentCycle;
        
        // Fetch field activities for this user during this payment cycle
        // Activities that overlap with the payment cycle period
        $activities = \App\Models\FieldActivity::where('created_by', $this->user_id)
            ->whereIn('status', ['approved', 'funded'])
            ->where(function($query) use ($cycle) {
                // Activity starts or ends within the payment cycle, or spans across it
                $query->whereBetween('start_date', [$cycle->start_date, $cycle->end_date])
                      ->orWhereBetween('end_date', [$cycle->start_date, $cycle->end_date])
                      ->orWhere(function($q) use ($cycle) {
                          $q->where('start_date', '<=', $cycle->start_date)
                            ->where('end_date', '>=', $cycle->end_date);
                      });
            })
            ->get();

        $totalProductivity = 0;

        foreach ($activities as $activity) {
            // Get filled days count from logs within the payment cycle period
            $filledDaysCount = $activity->logs()
                ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->count();

            $rate = floatval($activity->engagement_rate ?? 0);
            $engagementType = $activity->engagement_type ?? 'daily';

            // Calculate work based on engagement type
            if ($engagementType === 'daily' && $filledDaysCount > 0) {
                $totalProductivity += $rate * $filledDaysCount;
            } elseif ($engagementType === 'hourly' || $engagementType === 'fixed') {
                $totalProductivity += $rate;
            }
        }

        return $totalProductivity;
    }
}