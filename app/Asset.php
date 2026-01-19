<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku_code',
        'name',
        'quantity',
        'category',
        'imei_number',
        'serial_number',
        'status',
    ];

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function distributions()
    {
        return $this->hasMany(AssetDistribution::class);
    }

}
