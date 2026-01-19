<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class SupportIssue extends Model
{
    protected $fillable = [
        'user_id', 
        'category_id',
        'priority', 
        'status', 
        'subject', 
        'content', 
        'attachment',
        'escalated_by',
        'escalated_at'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'category_id' => 'integer',
        'priority' => 'string',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(IssuesCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getAttachmentAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function escalatedByUser()
    {
        return $this->belongsTo(User::class, 'escalated_by');
    }
}
