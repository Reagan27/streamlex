<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldReportAttachment extends Model
{
    protected $fillable = [
        'field_report_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(FieldReport::class);
    }
}