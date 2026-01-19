<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class BotTemplate extends Model
{
    protected $table = 'bot_templates';

    protected $fillable = [
        'name',
        'slug',
        'message',
        'placeholders',
        'is_active',
    ];

    protected $casts = [
        'placeholders' => 'array',
        'is_active' => 'boolean',
    ];
}