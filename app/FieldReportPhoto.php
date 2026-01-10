<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldReportPhoto extends Model
{
    protected $fillable = [
        'field_report_id',
        'photo_path',
        'caption',
        'taken_at'
    ];

    protected $casts = [
        'taken_at' => 'datetime'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(FieldReport::class);
    }
}