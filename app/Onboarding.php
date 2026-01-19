<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    protected $table = 'onboarding';

    protected $fillable = [
        'user_id',
        'personal_info_completed',
        'banking_details_completed',
        'documents_uploaded',
        'contract_signed'
    ];

    protected $casts = [
        'personal_info_completed' => 'boolean',
        'banking_details_completed' => 'boolean',
        'documents_uploaded' => 'boolean',
        'contract_signed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}