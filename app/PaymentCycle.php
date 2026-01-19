<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class PaymentCycle extends Model
{
    protected $fillable = ['description', 'start_date', 'end_date', 'is_invoicable'];

    protected $dates = ['start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_invoicable' => 'boolean'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}