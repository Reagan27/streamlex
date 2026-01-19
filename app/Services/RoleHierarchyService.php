<?php

namespace Vanguard\Services;

use Vanguard\Asset;
use Vanguard\User;
use Vanguard\Role;

class RoleHierarchyService
{
    private $hierarchy = [
        'Admin' => ['Regional_Coordinator', 'County_Coordinator', 'Supervisor', 'Field_Officer'],
        'Manager' => ['Regional_Coordinator', 'County_Coordinator', 'Supervisor', 'Field_Officer'],
        'Regional_Coordinator' => ['County_Coordinator', 'Supervisor', 'Field_Officer'],
        'County_Coordinator' => ['Supervisor', 'Field_Officer'],
        'Supervisor' => ['Field_Officer'],
        'Field_Officer' => []
    ];

    /**
     * Determines if the approver can approve the user's contract based on role hierarchy.
     *
     * @param User $approver
     * @param User $user
     * @param bool $finalApproval Indicates whether it's a final approval step.
     * @return bool
     */
    public function canApprove(User $approver, User $user): bool
    {
        $approverRole = $approver->role->name;
        $userRole = $user->role->name;

        if (!isset($this->hierarchy[$approverRole])) {
            return false;
        }

        return in_array($userRole, $this->hierarchy[$approverRole]);
    }

    /**
     * Determines if the admin can accept the user's contract signature.
     *
     * @param User $admin
     * @param User $user
     * @return bool
     */
    public function canAccept(User $admin, User $user): bool
    {
        return ($admin->role->name === 'Admin' || $admin->role->name === 'Manager') 
            && $user->contractSignature 
            && $user->contractSignature->status === 'approved';
    }

    /**
     * Determines if the viewer can access the user based on role hierarchy or direct ownership.
     *
     * @param User $viewer
     * @param User $user
     * @return bool
     */
    public function canAccess(User $viewer, User $user): bool
    {
        $viewerRole = $viewer->role->name;
        $userRole = $user->role->name;

        if ($viewerRole === 'Admin' || $viewerRole === 'Manager') {
            return true;
        }

        if (!isset($this->hierarchy[$viewerRole])) {
            return false;
        }

        return in_array($userRole, $this->hierarchy[$viewerRole]) || $viewer->id === $user->id;
    }

    /**
     * Fetch the assignable roles based on the current user's role.
     *
     * @param string $role The role of the current user.
     * @return array An array of role names.
     */
    public function getAssignableRoles(string $role): array
    {
        return $this->hierarchy[$role] ?? [];
    }

    /**
     * Fetch all subordinate roles based on the given role, including nested subordinates.
     *
     * @param string $role The role of the current user.
     * @return array An array of subordinate role names.
     */
    public function getAllSubordinateRoles(string $role): array
    {
        if (!isset($this->hierarchy[$role])) {
            return [];
        }

        $subordinates = $this->hierarchy[$role];
        foreach ($this->hierarchy[$role] as $subordinateRole) {
            $subordinates = array_merge($subordinates, $this->getAllSubordinateRoles($subordinateRole));
        }

        return array_unique($subordinates);
    }

    /**
     * Get the role IDs for a given set of role names.
     *
     * @param array $roleNames An array of role names.
     * @return array An array of role IDs corresponding to the role names.
     */
    public function getRoleIdsByNames(array $roleNames): array
    {
        return Role::whereIn('name', $roleNames)->pluck('id')->toArray();
    }

    /**
     * Filter roles based on the logged-in user's role and return specific roles for them.
     * For example, if the user is an Admin, only show County Coordinators and Regional Coordinators.
     *
     * @param User $user The logged-in user.
     * @param array $rolesToFilter An array of role names to filter.
     * @return array Filtered roles based on the user's permissions.
     */
    public function filterRolesByUser(User $user, array $rolesToFilter): array
    {
        if ($this->hasRole($user, 'Admin') || $this->hasRole($user, 'Manager')) {
            return array_filter($rolesToFilter, function ($role) {
                return in_array($role, ['County_Coordinator', 'Regional_Coordinator']);
            });
        }

        return $this->getAssignableRoles($user->role->name);
    }

    /**
     * Check if the user has a specific role by name.
     *
     * @param User $user
     * @param string $roleName
     * @return bool
     */
    public function hasRole(User $user, string $roleName): bool
    {
        return $user->role->name === $roleName;
    }

    /**
     * Check if a role exists in the hierarchy.
     *
     * @param string $roleName
     * @return bool
     */
    public function roleExists(string $roleName): bool
    {
        return isset($this->hierarchy[$roleName]);
    }

    /**
     * Return the role hierarchy array, useful for debug or other services.
     *
     * @return array
     */
    public function getRoleHierarchy(): array
    {
        return $this->hierarchy;
    }

    public function getAssignableUsers(User $currentUser, Asset $asset)

    {
        $subordinateRoles = $this->getAllSubordinateRoles($currentUser->role->name);
        $subordinateRoleIds = $this->getRoleIdsByNames($subordinateRoles);

        return User::whereIn('role_id', $subordinateRoleIds)->get();
    }

    public function getDistributableUsers(User $currentUser, Asset $asset)
    {
        $subordinateRoles = $this->getAllSubordinateRoles($currentUser->role->name);
        $subordinateRoleIds = $this->getRoleIdsByNames($subordinateRoles);

        // Field Officers cannot receive distributed assets
        $distributableRoleIds = array_diff($subordinateRoleIds, [Role::where('name', 'Field_Officer')->first()->id]);

        return User::whereIn('role_id', $distributableRoleIds)->get();
    }

    public function canDistribute(User $distributor, User $recipient): bool
{
    $distributorRole = $distributor->role->name;
    $recipientRole = $recipient->role->name;

    if (!isset($this->hierarchy[$distributorRole])) {
        return false;
    }

    if (!in_array($recipientRole, $this->hierarchy[$distributorRole])) {
        return false;
    }

    // Check geo-restrictions
    if ($distributorRole === 'Regional_Coordinator') {
        return $distributor->counties->contains($recipient->county_id);
    } elseif ($distributorRole === 'County_Coordinator') {
        return $distributor->county_id === $recipient->county_id;
    } elseif ($distributorRole === 'Supervisor') {
        return $distributor->subcounty_id === $recipient->subcounty_id;
    }

    return true;
}

public function canProcessReturn(User $processor, User $returnee): bool
{
    // The logic here is similar to canDistribute, but you might want to allow
    // users to return assets to their direct supervisors as well.
    return $this->canDistribute($processor, $returnee) || $returnee->supervisor_id === $processor->id;
}
}
