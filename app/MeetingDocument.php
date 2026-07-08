<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'meeting_id',
        'document_type',
        'file_name',
        'file_path',
        'file_mime_type',
        'file_size',
        'remarks',
        'uploaded_by',
    ];

    /**
     * Get the meeting this document belongs to
     */
    public function meeting()
    {
        return $this->belongsTo('Vanguard\Meeting');
    }

    /**
     * Get the user who uploaded this document
     */
    public function uploadedBy()
    {
        return $this->belongsTo('Vanguard\User', 'uploaded_by');
    }

    /**
     * Scope to get minutes
     */
    public function scopeMinutes($query)
    {
        return $query->where('document_type', 'Minutes');
    }

    /**
     * Scope to get audio files
     */
    public function scopeAudio($query)
    {
        return $query->where('document_type', 'Audio');
    }

    /**
     * Scope to get video files
     */
    public function scopeVideo($query)
    {
        return $query->where('document_type', 'Video');
    }

    /**
     * Scope to get attendance documents
     */
    public function scopeAttendance($query)
    {
        return $query->where('document_type', 'Attendance');
    }

    /**
     * Scope to get transcripts
     */
    public function scopeTranscript($query)
    {
        return $query->where('document_type', 'Transcript');
    }
}
