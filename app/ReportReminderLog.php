<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class ReportReminderLog extends Model
{
    protected $table = 'report_reminder_logs';

    protected $fillable = [
        'user_id',
        'report_type',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
