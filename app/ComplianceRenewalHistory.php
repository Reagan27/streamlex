<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class ComplianceRenewalHistory extends Model
{
    protected $table = 'compliance_renewal_histories';

    protected $fillable = ['compliance_document_id', 'renewed_on', 'notes', 'document_path', 'document_name', 'renewal_type'];

    protected $casts = [
        'renewed_on' => 'date',
    ];

    public function document()
    {
        return $this->belongsTo(ComplianceDocument::class, 'compliance_document_id');
    }
}
