<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RateableItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'expires_at',
        'created_by',
        'slug'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->slug = Str::slug($item->title) . '-' . Str::random(8);
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(RateableAttribute::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }

    public function getOverallAverageRatingAttribute()
    {
        return $this->attributes()
            ->join('attribute_ratings', 'rateable_attributes.id', '=', 'attribute_ratings.rateable_attribute_id')
            ->avg('attribute_ratings.rating') ?? 0;
    }
}