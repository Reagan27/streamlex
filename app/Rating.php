<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rating extends Model
{
    protected $fillable = [
        'rateable_item_id',
        'comment',
        'rater_name',
        'rater_email',
        'location',
        'ip_address',
        'hidden'
    ];

    protected $casts = [
        'hidden' => 'boolean'
    ];

    public function rateableItem(): BelongsTo
    {
        return $this->belongsTo(RateableItem::class);
    }

    public function attributeRatings(): HasMany
    {
        return $this->hasMany(AttributeRating::class);
    }
}