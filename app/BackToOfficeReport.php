<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BackToOfficeReport extends Model
{
    protected $fillable = [
        'project_name',
        'area',
        'unit',
        'activity_date',
        'reported_by',
        'activity',
        'venue',
        'participants_by_age',
        'participants_by_gender',
        'participants_disability',
        'participants_vmgs',
        'total_participants',
        'introduction',
        'objective',
        'budget_expenditure',
        'output',
        'key_highlights',
        'challenges_and_risks',
        'best_practices',
        'lessons_learnt',
        'recommendations',
        'way_forward',
        'prepared_by',
        'prepared_date',
        'signature',
        'annexes',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'approval_comments',
        'county_id',
        'participants',
        'budget'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'activity_date' => 'date',
        'prepared_date' => 'date',
        'participants' => 'array',
        'budget' => 'array',
        'annexes' => 'array'
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
        return $this->hasMany(BackToOfficeReportAttachment::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function getCanApproveAttribute()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('back-to-office-reports.approve')) {
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
