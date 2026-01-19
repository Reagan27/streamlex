<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractVersion extends Model
{
    use HasFactory;

    protected $table = 'contract_versions';

    protected $fillable = [
        'contract_id',
        'status',
        'description',
        'authority_signature',
        'authority_name',
        'authority_designation',
        'change_reason',
        'changed_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the contract that owns the version.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(AdminContract::class, 'contract_id');
    }

    /**
     * Get the user who made the change.
     */
    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}