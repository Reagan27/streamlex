<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    protected $fillable = ['name'];

    public function adminContracts()
{
    return $this->belongsToMany(AdminContract::class, 'admin_contract_county');
}

    public function subcounties()
    {
        return $this->hasMany(Subcounty::class);
    }

    public function countyCoordinators()
    {
        return $this->hasMany(User::class, 'county_id');
    }

    public function regionalCoordinators()
{
    return $this->belongsToMany(User::class, 'regional_coordinator_counties', 'county_id', 'user_id');
}
public function users()
{
    return $this->hasMany(User::class);
}

public function getNameAttribute($value)
{
    return ucfirst($value);
}
}
