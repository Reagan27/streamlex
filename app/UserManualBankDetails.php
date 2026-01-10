<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class UserManualBankDetails extends Model
{
    protected $fillable = [
        'user_id',
        'manual_branch_name',
        'manual_branch_code',
        'use_manual_details'
    ];

    protected $casts = [
        'use_manual_details' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}