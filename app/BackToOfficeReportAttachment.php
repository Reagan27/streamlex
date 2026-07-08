<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackToOfficeReportAttachment extends Model
{
    protected $fillable = [
        'back_to_office_report_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
        'attachment_type'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(BackToOfficeReport::class);
    }
}
