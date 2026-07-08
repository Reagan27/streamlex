<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class DocumentAcknowledgement extends Model
{
    protected $table = 'document_acknowledgements';

    protected $fillable = [
        'title',
        'description',
        'content',
        'original_file_path',
        'original_file_name',
        'created_by',
        'signature_page',
        'signature_x',
        'signature_y',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(DocumentAcknowledgementAssignment::class, 'document_acknowledgement_id');
    }

    public function logs()
    {
        return $this->hasMany(DocumentAcknowledgementLog::class, 'document_acknowledgement_id');
    }
}
