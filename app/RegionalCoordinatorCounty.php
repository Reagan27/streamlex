<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionalCoordinatorCounty extends Model
{
    protected $table = 'regional_coordinator_counties';

    protected $fillable = [
        'user_id',
        'county_id',
    ];

    /**
     * Get the user that owns the regional coordinator county.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the county that the regional coordinator is assigned to.
     */
    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }
}