<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BotIssue extends Model
{
    protected $table = 'bot_issues';

    protected $fillable = [
        'issue_id',
        'user_id',
        'phone_number',
        'category',
        'description',
        'status',
        'assigned_to',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($issue) {
            if (empty($issue->issue_id)) {
                $issue->issue_id = 'ISS-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}