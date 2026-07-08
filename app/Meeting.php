<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'meeting_type',
        'status',
        'meeting_date',
        'start_time',
        'end_time',
        'venue_name',
        'address',
        'room_number',
        'location_map_link',
        'meeting_link',
        'meeting_platform',
        'created_by',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this meeting
     */
    public function organizer()
    {
        return $this->belongsTo('Vanguard\User', 'created_by');
    }

    /**
     * Get all participants for this meeting
     */
    public function participants()
    {
        return $this->hasMany('Vanguard\MeetingParticipant');
    }

    /**
     * Get all documents for this meeting
     */
    public function documents()
    {
        return $this->hasMany('Vanguard\MeetingDocument');
    }

    /**
     * Get all action items for this meeting
     */
    public function actions()
    {
        return $this->hasMany('Vanguard\MeetingAction');
    }

    /**
     * Get the count of confirmed attendees
     */
    public function confirmedParticipants()
    {
        return $this->participants()
            ->whereIn('attendance_status', ['Confirmed', 'Attended']);
    }

    /**
     * Get the count of attended participants
     */
    public function attendedParticipants()
    {
        return $this->participants()
            ->where('attendance_status', 'Attended');
    }

    /**
     * Scope to get upcoming meetings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('meeting_date', '>=', now())
            ->whereIn('status', ['Scheduled', 'In Progress']);
    }

    /**
     * Scope to get completed meetings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope to get draft meetings
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }
}
