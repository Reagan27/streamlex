<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RateableAttribute extends Model
{
    protected $fillable = [
        'rateable_item_id',
        'name',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function rateableItem(): BelongsTo
    {
        return $this->belongsTo(RateableItem::class);
    }   
    
    public function ratings(): HasMany
    {
        return $this->hasMany(AttributeRating::class, 'rateable_attribute_id');
    }
}