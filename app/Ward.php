<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['name', 'subcounty_id'];

    public function subcounty()
    {
        return $this->belongsTo(Subcounty::class);
    }

    public function fieldOfficers()
    {
        return $this->hasMany(User::class, 'ward_id');
    }
}