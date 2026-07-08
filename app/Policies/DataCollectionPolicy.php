<?php

namespace App\Policies;

use Vanguard\User;
use App\Models\DataCollection;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataCollectionPolicy
{
    use HandlesAuthorization;

    public function manage(User $user)
    {
        // Only allow admins to manage
        return $user->role && $user->role->name === 'Admin';
    }

    public function view(User $user, DataCollection $dataCollection)
    {
        // All authenticated users can view active entries
        return $dataCollection->status === true;
    }
}
