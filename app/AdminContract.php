<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminContract extends Model
{
    protected $table = 'admin_contracts';

    protected $fillable = [
        'title',
        'start_date',
        'number_of_days',
        'description',
        'role_id',
        'status',
        'authority_name',
        'authority_designation',
        'authority_signature',
        'active_for_onboarding' 
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'number_of_days' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'active_for_onboarding' => 'boolean'
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DROP = 'drop';

    public function getStatusOptions()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_DROP => 'Dropped'
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function counties()
    {
        return $this->belongsToMany(County::class, 'admin_contract_county');
    }

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getEndDateAttribute()
    {
        if ($this->start_date && $this->number_of_days) {
            return $this->start_date->copy()->addDays($this->number_of_days - 1);
        }
        return null;
    }
    public function versions(): HasMany
    {
        return $this->hasMany(ContractVersion::class, 'contract_id');
    }
}