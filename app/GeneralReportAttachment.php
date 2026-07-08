<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralReportAttachment extends Model
{
    protected $fillable = [
        'general_report_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(GeneralReport::class);
    }
}
