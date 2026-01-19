<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContractSignature extends Model 
{
    use HasFactory;

    protected $table = 'user_contract_signatures';

    protected $fillable = [
        'user_id',
        'contract_id',
        'signature',
        'agreed_at',
        'status',
        'decline_reason',
        'termination_reason',
        'termination_date',
        'terminated_at',
        'terminated_by',
        'activation_date',
        'completion_date',
        'completion_reason',
        'completion_notes',
        'transfer_from_county',
        'transfer_reason',
        'transfer_date',
        'transfer_type'
    ];

    protected $attributes = [
        'status' => 'draft'
    ];

    protected $casts = [
        'agreed_at' => 'datetime',
        'terminated_at' => 'datetime',
        'activation_date' => 'datetime',
        'completion_date' => 'datetime',
        'termination_date' => 'date',
        'transfer_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contract()
    {
        return $this->belongsTo(AdminContract::class, 'contract_id');
    }

    public function terminatedBy()
    {
        return $this->belongsTo(User::class, 'terminated_by');
    }

    public function isActive()
    {
        return in_array($this->status, ['approved', 'accepted']);
    }

    public function canBeRestarted()
    {
        return in_array($this->status, ['declined', 'terminated', 'expired', 'inactive']);
    }
}