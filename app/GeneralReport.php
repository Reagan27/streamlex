<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralReport extends Model
{
    protected $fillable = [
        'title',
        'category', // New: category field
        'subcategory',
        'content',
        'summary',
        'recommendations',
        'status',
        'created_by',
        'approved_by',
        'county_id',
        'approved_at',
        'approval_comments',
        'location',
        'report_date',
        'start_time',
        'end_time',
        'objectives',
        'challenges_faced'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(GeneralReportAttachment::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(GeneralReportActivity::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function getCanApproveAttribute()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('general-reports.approve')) {
            return false;
        }

        if ($user->hasRole(['Admin', 'Manager'])) {
            return true;
        }

        if ($user->hasRole('Regional_Coordinator')) {
            return $user->counties->contains('id', $this->county_id);
        }

        if ($user->hasRole('County_Coordinator')) {
            return $user->county_id === $this->county_id;
        }

        return false;
    }
}
