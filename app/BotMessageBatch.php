<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class BotMessageBatch extends Model
{
    protected $table = 'bot_message_batches';

    protected $fillable = [
        'batch_id',
        'user_id',
        'total_count',
        'sent_count',
        'failed_count',
        'status',
        'filters',
    ];

    protected $casts = [
        'filters' => 'array',
    ];

    public function messages()
    {
        return $this->hasMany(BotMessage::class, 'batch_id', 'batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}