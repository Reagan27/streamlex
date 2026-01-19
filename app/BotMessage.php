<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class BotMessage extends Model
{
    protected $table = 'bot_messages';

    protected $fillable = [
        'batch_id',
        'user_id',
        'name',
        'phone',
        'recipient',
        'message',
        'attachments', 
        'category',
        'date',
        'group_id',
        'company_id',
        'ussid',
        'status',
        'status_message',
        'message_id',
        'message_cost',
        'response',
        'sent_at',
        'is_rating',
        'rating_type',
        'scale_min',
        'scale_max',
        'allow_comment',
        'allow_skip',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'date' => 'datetime',
        'message_cost' => 'decimal:2',
        'attachments' => 'array',
        'is_rating' => 'boolean',
        'allow_comment' => 'boolean',
        'allow_skip' => 'boolean',
    ];

    
    const STATUS_QUEUED = 0;      
    const STATUS_SENT = 1;
    const STATUS_FAILED = 2;
    const STATUS_DELIVERED = 3;
    const STATUS_UNDELIVERED = 4;

    public function batch()
    {
        return $this->belongsTo(BotMessageBatch::class, 'batch_id', 'batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getStatusDescriptionAttribute()
    {
        switch ($this->status) {
            case self::STATUS_QUEUED:
                return 'Queued';
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
}