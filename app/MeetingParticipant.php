<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingParticipant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'meeting_id',
        'user_id',
        'role',
        'attendance_status',
        'remarks',
    ];

    /**
     * Get the meeting this participant belongs to
     */
    public function meeting()
    {
        return $this->belongsTo('Vanguard\Meeting');
    }

    /**
     * Get the user this participant refers to
     */
    public function user()
    {
        return $this->belongsTo('Vanguard\User');
    }

    /**
     * Scope to get confirmed participants
     */
    public function scopeConfirmed($query)
    {
        return $query->where('attendance_status', 'Confirmed');
    }

    /**
     * Scope to get attended participants
     */
    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'Attended');
    }

    /**
     * Scope to get invited participants
     */
    public function scopeInvited($query)
    {
        return $query->where('attendance_status', 'Invited');
    }
}
