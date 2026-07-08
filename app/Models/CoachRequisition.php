<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vanguard\User;        
use Vanguard\County;      

class CoachRequisition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'requested_by',
        'position_title',
        'number_of_coaches',
        'start_date',
        'end_date',
        'work_arrangement',
        'reporting_to',
        'budget_line',
        'budget_code',
        'monthly_cost',
        'total_cost',
        'status',
        'county_id',
        'justification',
        'roles_responsibilities',
        'engagement_type',
        'engagement_rate',
        'engagement_total',
    ];

    public function proposedCoaches()
    {
        return $this->hasMany(RequisitionProposedCoach::class);
    }

    public function approvals()
    {
        return $this->hasMany(RequisitionApproval::class)->orderBy('approval_level');
    }

    public function currentPendingApproval()
    {
        return $this->approvals
            ->where('status', 'pending')
            ->sortBy('approval_level')
            ->first();
    }

    public function pendingApprovalForRole(?string $role)
    {
        if (!$role) {
            return null;
        }

        $currentPending = $this->currentPendingApproval();
        if (!$currentPending) {
            return null;
        }

        return $this->approvals
            ->where('role', $role)
            ->where('status', 'pending')
            ->where('approval_level', $currentPending->approval_level)
            ->first();
    }

    public function canUserApprove($user)
    {
        if (!$user || $user->id === $this->requested_by) {
            return false;
        }

        $userRole = $user->role ? $user->role->name : null;
        if (!$userRole) {
            return false;
        }

        $currentPending = $this->currentPendingApproval();
        if (!$currentPending) {
            return false;
        }

        return $userRole === 'Admin' || $currentPending->role === $userRole;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by'); // ✅ fixed
    }

    public function county()
    {
        return $this->belongsTo(County::class); // ✅ fixed
    }
    public function fieldActivities()
    {
        return $this->hasMany(FieldActivity::class, 'coach_requisition_id');
    }
}