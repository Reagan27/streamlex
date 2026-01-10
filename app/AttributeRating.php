<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeRating extends Model
{
    protected $fillable = [
        'rating_id',
        'rateable_attribute_id',
        'rating'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    public function rating(): BelongsTo
    {
        return $this->belongsTo(Rating::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(RateableAttribute::class, 'rateable_attribute_id');
    }
}