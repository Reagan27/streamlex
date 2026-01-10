<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class FieldReportActivity extends Model
{
    protected $fillable = [
        'field_report_id',
        'activity_type',
        'description',
        'start_time',
        'end_time',
        'outcomes',
        'resources_used',
        'challenges'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(FieldReport::class);
    }
}