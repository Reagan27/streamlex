<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class ComplianceDocument extends Model
{
    use SoftDeletes;

    protected $table = 'compliance_documents';

    protected $fillable = [
        'category_id',
        'name',
        'reference_number',
        'regulatory_authority',
        'department',
        'responsible_officer',
        'status',
        'renewal_frequency',
        'reminder_period',
        'issue_date',
        'expiry_date',
        'next_renewal_date',
        'document_path',
        'document_name',
        'description',
        'last_reminder_at',
        'last_reminder_stage',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'next_renewal_date' => 'date',
        'last_reminder_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ComplianceCategory::class, 'category_id');
    }

    public function renewals()
    {
        return $this->hasMany(ComplianceRenewalHistory::class, 'compliance_document_id')->latest();
    }

    public function getStatusForDisplayAttribute(): string
    {
        if ($this->expiry_date && $this->expiry_date->lt(Carbon::today())) {
            return 'expired';
        }

        if ($this->expiry_date && $this->expiry_date->lte(Carbon::today()->addDays(30))) {
            return 'expiring_soon';
        }

        return 'active';
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        return Carbon::today()->startOfDay()->diffInDays($this->expiry_date->copy()->startOfDay(), false);
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status_for_display) {
            'expired' => 'danger',
            'expiring_soon' => 'warning',
            default => 'success',
        };
    }

    public function reminderStageForDays(int $days): ?string
    {
        if ($days <= 4) {
            return 'daily';
        }

        return match (true) {
            $days <= 7 => '7_days',
            $days <= 14 => '14_days',
            $days <= 30 => '30_days',
            $days <= 60 => '60_days',
            $days <= 90 => '90_days',
            default => null,
        };
    }
}
