<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;


class AssetDistribution extends Model
{
    protected $fillable = [
        'asset_id',
        'distributed_to',
        'distributed_by',
        'quantity',
        'comments',
        'distributed_at',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function distributedTo()
    {
        return $this->belongsTo(User::class, 'distributed_to');
    }

    public function distributedBy()
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }
}
