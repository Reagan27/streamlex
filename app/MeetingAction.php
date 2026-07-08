<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingAction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'meeting_id',
        'title',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the meeting this action belongs to
     */
    public function meeting()
    {
        return $this->belongsTo('Vanguard\Meeting');
    }

    /**
     * Get the user this action is assigned to
     */
    public function assignedTo()
    {
        return $this->belongsTo('Vanguard\User', 'assigned_to');
    }

    /**
     * Get the user who created this action
     */
    public function createdBy()
    {
        return $this->belongsTo('Vanguard\User', 'created_by');
    }

    /**
     * Scope to get open actions
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }

    /**
     * Scope to get in progress actions
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }

    /**
     * Scope to get completed actions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope to get overdue actions
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'Overdue')
            ->orWhere(function ($q) {
                $q->where('status', '!=', 'Completed')
                    ->where('due_date', '<', now()->toDateString());
            });
    }
}
