<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class BotRating extends Model
{
    protected $table = 'bot_ratings';

    protected $fillable = [
        'session_id',
        'user_id',
        'phone_number',
        'rating_type',
        'rateable_id',
        'rateable_type',
        'rating_score',
        'thumb_rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rateable()
    {
        return $this->morphTo();
    }
}