<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendance extends Model
{
    use HasFactory;

    protected $table = 'event_attendances';

    protected $fillable = [
        'training_event_id',
        'user_id',
        'name',
        'id_number',
        'phone_number',
        'email',
        'signature',
        'is_authenticated_user',
        'is_registration',
        'days_attended',
        'total_amount',
        'completed',
        'title',
        'designation',
        'phone_verified',
        'phone_verification_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_authenticated_user' => 'boolean',
    ];
    
    public function event(): BelongsTo
    {
        return $this->belongsTo(TrainingEvent::class, 'training_event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}