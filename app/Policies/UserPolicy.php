<?php

namespace Vanguard\Policies;

use Vanguard\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can update assigned counties
     */
    public function updateCounties(User $user, User $targetUser)
    {
        return \Gate::allows('manage-counties', $targetUser);
    }

    /**
     * Determine if user can update user details
     */
    public function update(User $user, User $targetUser)
    {
        return \Gate::allows('update-user', $targetUser);
    }

    /**
     * Determine if user can view sensitive information
     */
    public function viewSensitiveInfo(User $user, User $targetUser)
    {
        return \Gate::allows('view-sensitive-info', $targetUser);
    }

    public function updateBankDetails(User $authUser, User $user): bool
    {
        // Allow admins and managers to update bank details
        if ($authUser->isAdmin() || $authUser->hasRole('Manager')) {
            return true;
        }

        // Allow users to update their own bank details
        return $authUser->id === $user->id;
    }
    /**
     * Determine if user can manage sessions
     */
    public function manageSessions(User $user, User $targetUser)
    {
        if ($user->hasPermission('users.manage')) {
            return true;
        }

        return $user->id === $targetUser->id;
    }

    /**
     * Give admin users all permissions
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
    }
}
