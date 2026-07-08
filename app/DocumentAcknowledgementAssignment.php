<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class DocumentAcknowledgementAssignment extends Model
{
    protected $table = 'document_acknowledgement_assignments';

    protected $fillable = [
        'document_acknowledgement_id',
        'user_id',
        'status',
        'viewed_at',
        'signed_at',
        'ip_address',
        'user_agent',
        'signed_file_path',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'signed_at' => 'datetime',
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
