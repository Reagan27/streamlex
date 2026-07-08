<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class DocumentAcknowledgementLog extends Model
{
    protected $table = 'document_acknowledgement_logs';

    protected $fillable = [
        'document_acknowledgement_id',
        'user_id',
        'action',
        'ip_address',
        'user_agent',
    ];

    public function document()
    {
        return $this->belongsTo(DocumentAcknowledgement::class, 'document_acknowledgement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
