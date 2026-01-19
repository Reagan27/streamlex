<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MismatchedPayment extends Model
{
    protected $fillable = [
        'payment_cycle_id',
        'id_number',
        'imported_name',
        'productivity',
        'amount_payable',
        'net_payable', 
        'taxable_amount',
        'tax',
        'advance_pay',
        'status',
        'resolution_notes'
    ];

    public function paymentCycle()
    {
        return $this->belongsTo(PaymentCycle::class);
    }

    // Method to attempt matching and transfer to payments table
    public function attemptMatch()
    {
        $userDocument = UserDocument::where('id_number', $this->id_number)->first();

        if ($userDocument) {
            DB::transaction(function () use ($userDocument) {
                // Create payment record
                Payment::create([
                    'user_id' => $userDocument->user_id,
                    'payment_cycle_id' => $this->payment_cycle_id,
                    'id_number' => $this->id_number,
                    'imported_name' => $this->imported_name,
                    'productivity' => $this->productivity,
                    'amount_payable' => $this->amount_payable,
                    'net_payable' => $this->net_payable,
                    'taxable_amount' => $this->taxable_amount,
                    'tax' => $this->tax,
                    'advance_pay' => $this->advance_pay,
                    'status' => $this->status,
                ]);

                // Delete this mismatched record
                $this->delete();
            });

            return true;
        }

        return false;
    }
}