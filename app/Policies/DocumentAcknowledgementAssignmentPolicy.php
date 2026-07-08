<?php

namespace Vanguard\Policies;

use Vanguard\DocumentAcknowledgementAssignment;
use Vanguard\User;

class DocumentAcknowledgementAssignmentPolicy
{
    public function view(User $user, DocumentAcknowledgementAssignment $assignment): bool
    {
        return $user->id === $assignment->user_id || $user->hasPermission('compliance.view');
    }

    public function acknowledge(User $user, DocumentAcknowledgementAssignment $assignment): bool
    {
        return $user->id === $assignment->user_id && $assignment->status !== 'Signed';
    }

    public function download(User $user, DocumentAcknowledgementAssignment $assignment): bool
    {
        return $user->id === $assignment->user_id || $user->hasPermission('compliance.view');
    }
}
