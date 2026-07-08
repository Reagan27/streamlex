<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldActivity extends Model
{
    public function timeline()
    {
        return $this->hasMany(FieldActivityTimeline::class, 'activity_id');
    }
    protected $fillable = [
        'requisition_id',
        'coach_requisition_id',
        'title',
        'description',
        'team',
        'location',
        'start_date',
        'end_date',
        'status',
        'progress_pct',
        'budget_programme',
        'budget_transport',
        'actual_spent',
        'disbursed',
        'created_by',
        'engagement_type',
        'engagement_rate',
        'engagement_total',
    ];

    public function coachRequisition()
    {
        return $this->belongsTo(CoachRequisition::class, 'coach_requisition_id');
    }

    public function user()
    {
        // Use the correct User class from the Vanguard namespace
        return $this->belongsTo(\Vanguard\User::class, 'created_by');
    }

    public function expenses()
    {
        return $this->hasMany(FieldActivityExpense::class);
    }

    public function transportLogs()
    {
        return $this->hasMany(FieldActivityTransport::class);
    }

    public function documents()
    {
        return $this->hasMany(FieldActivityDocument::class);
    }

    public function approvals()
    {
        return $this->hasMany(FieldActivityApproval::class);
    }

    // Logsheet logs relationship
    public function logs()
    {
        return $this->hasMany(FieldActivityLog::class, 'field_activity_id');
    }

    public function getTotalPlannedBudgetAttribute()
    {
        return (float)($this->budget_programme ?? 0) + (float)($this->budget_transport ?? 0);
    }

    public function getBalanceToReturnAttribute()
    {
        return $this->total_planned_budget - (float)($this->actual_spent ?? 0);
    }

    public function getIsLinkedAttribute()
    {
        return !is_null($this->coach_requisition_id);
    }
}
