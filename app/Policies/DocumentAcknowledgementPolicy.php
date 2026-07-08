<?php

namespace Vanguard\Policies;

use Vanguard\DocumentAcknowledgement;
use Vanguard\User;

class DocumentAcknowledgementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('compliance.view');
    }

    public function view(User $user, DocumentAcknowledgement $document): bool
    {
        return $user->hasPermission('compliance.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('compliance.create');
    }

    public function update(User $user, DocumentAcknowledgement $document): bool
    {
        return $user->hasPermission('compliance.edit');
    }

    public function delete(User $user, DocumentAcknowledgement $document): bool
    {
        return $user->hasPermission('compliance.delete');
    }
}
