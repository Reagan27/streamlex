<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name'];

    public function counties()
    {
        return $this->belongsToMany(County::class, 'county_region');
    }
}
