<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldReportAttendee extends Model
{
    protected $fillable = [
        'field_report_id',
        'name',
        'organization',
        'role',
        'contact_number',
        'email',
        'arrival_time',
        'departure_time',
        'comments'
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(FieldReport::class);
    }
}
