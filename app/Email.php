<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';

    protected $fillable = [
        'recipient',
        'subject',
        'message',
        'status',
        'response',
        'batch_id',
        'sent_at',
        'email_id',
        'category',
        'user_id',
        'attachment',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_UNDELIVERED = 'undelivered';

    public function getStatusDescriptionAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_SENT:
                return 'Sent';
            case self::STATUS_FAILED:
                return 'Failed';
            case self::STATUS_DELIVERED:
                return 'Delivered';
            case self::STATUS_UNDELIVERED:
                return 'Undelivered';
            default:
                return 'Unknown';
        }
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
    }

    public function batch()
    {
        return $this->belongsTo(EmailBatch::class, 'batch_id', 'batchId');
    }
}
