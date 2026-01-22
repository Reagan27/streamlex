<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class BotAnnouncementView extends Model
{
    protected $table = 'bot_announcement_views';

    protected $fillable = [
        'message_id',
        'user_id',
        'phone_number',
        'session_id',
        'viewed_at',
        'platform',
        'metadata',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(BotMessage::class, 'message_id', 'id');
    }
}