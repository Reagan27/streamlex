<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model 
{
    protected $fillable = [
        'asset_id',
        'assigned_to',
        'assigned_by',
        'imei_number',
        'serial_number',
        'physical_condition',
        'assignment_status',
        'comments',
    ];

    protected $casts = [
        'physical_condition' => 'string',
        'assignment_status' => 'string',
    ];

    // Physical condition constants
    public const CONDITION_GOOD = 'Good';
    public const CONDITION_NEW = 'New';
    public const CONDITION_DAMAGED = 'Damaged';
    public const CONDITION_POOR = 'Poor';

    // Assignment status constants
    public const STATUS_ASSIGNED = 'Assigned';
    public const STATUS_RETURNED = 'Returned';

    public static function getPhysicalConditions(): array
    {
        return [
            self::CONDITION_GOOD,
            self::CONDITION_NEW,
            self::CONDITION_DAMAGED,
            self::CONDITION_POOR,
        ];
    }

    public static function getAssignmentStatuses(): array
    {
        return [
            self::STATUS_ASSIGNED,
            self::STATUS_RETURNED,
        ];
    }

    public function scopeSearch(Builder $query, ?string $searchTerm): Builder
    {
        if (!$searchTerm) {
            return $query;
        }

        return $query->where(function ($q) use ($searchTerm) {
            $q->where('imei_number', 'like', "%{$searchTerm}%")
              ->orWhere('serial_number', 'like', "%{$searchTerm}%")
              ->orWhereHas('assignedTo', function ($userQuery) use ($searchTerm) {
                  $userQuery->where('first_name', 'like', "%{$searchTerm}%")
                           ->orWhere('last_name', 'like', "%{$searchTerm}%");
              })
              ->orWhereHas('assignedBy', function ($userQuery) use ($searchTerm) {
                  $userQuery->where('first_name', 'like', "%{$searchTerm}%")
                           ->orWhere('last_name', 'like', "%{$searchTerm}%");
              });
        });
    }

    public function scopeFilterByCondition(Builder $query, ?string $condition): Builder
    {
        if (!$condition) {
            return $query;
        }
        return $query->where('physical_condition', $condition);
    }

    public function scopeFilterByStatus(Builder $query, ?string $status): Builder
    {
        if (!$status) {
            return $query;
        }
        return $query->where('assignment_status', $status);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}