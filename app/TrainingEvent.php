<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TrainingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'venue_name',
        'venue_latitude',
        'venue_longitude',
        'location_radius',
        'enforce_location',
        'location_token',
        'county_id',
        'description',
        'start_date',
        'end_date',
        'form_expires_at',
        'daily_amount',
        'created_by',
        'slug'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'form_expires_at' => 'datetime',
        'daily_amount' => 'decimal:2',
        'venue_latitude' => 'decimal:8',
        'venue_longitude' => 'decimal:8',
        'enforce_location' => 'boolean'
    ];

    public function isWithinVenueRadius($lat, $lng): bool
    {
        if (!$this->enforce_location || 
            !$this->venue_latitude || 
            !$this->venue_longitude || 
            !$this->location_radius) {
            return true;
        }

        $earthRadius = 6371000;

        $latFrom = deg2rad($this->venue_latitude);
        $lonFrom = deg2rad($this->venue_longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        
        $distance = $angle * $earthRadius;

        return $distance <= $this->location_radius;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->slug = Str::slug($event->name) . '-' . Str::random(8);
            $event->total_days = Carbon::parse($event->start_date)
                ->diffInDays(Carbon::parse($event->end_date)) + 1;
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class, 'training_event_id');
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->form_expires_at);
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
