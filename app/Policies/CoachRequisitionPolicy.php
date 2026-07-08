<?php
namespace App\Policies;

use Vanguard\User;
use App\Models\CoachRequisition;

class CoachRequisitionPolicy
{
    private function isApprover(User $user, ?CoachRequisition $requisition = null): bool
    {
        // Get all roles with 'approve requisition' permission
        $rolesWithPermission = \Vanguard\Role::whereHas('permissions', function($q) {
            $q->where('name', 'approve requisition');
        })->pluck('name')->toArray();

        // If only one role has the permission, allow any user with that role to approve at any stage
        if (count($rolesWithPermission) === 1) {
            return $user->role && $user->role->name === $rolesWithPermission[0];
        }

        // If multiple roles, require approval chain logic (user can only approve at their stage)
        // If requisition is provided, check if there is a pending approval for user's role
        if ($requisition) {
            $pendingApproval = $requisition->approvals()
                ->where('role', $user->role->name ?? null)
                ->where('status', 'pending')
                ->first();
            return (bool) $pendingApproval;
        }

        // If no requisition context, just check if user has one of the roles
        return in_array($user->role->name ?? '', $rolesWithPermission);
    }

    public function view(User $user, ?CoachRequisition $requisition = null): bool
    {
        return true;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function approve(User $user, ?CoachRequisition $requisition = null): bool
    {
        return $this->isApprover($user, $requisition);
    }

    public function approveAny(User $user): bool
    {
        return $this->isApprover($user);
    }

    public function accept(User $user, ?CoachRequisition $requisition = null): bool
    {
        return $this->isApprover($user, $requisition);
    }

    public function reject(User $user, ?CoachRequisition $requisition = null): bool
    {
        return $this->isApprover($user, $requisition);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ?CoachRequisition $requisition = null): bool
    {
        return $this->isApprover($user, $requisition);
    }

    public function delete(User $user, ?CoachRequisition $requisition = null): bool
    {
        return $this->isApprover($user, $requisition);
    }
}