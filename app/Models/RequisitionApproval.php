<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionApproval extends Model
{
    protected $fillable = [
        'coach_requisition_id', 'approval_level', 'role', 'status', 'comments', 'approver_name', 'approver_user_id', 'approved_at'
    ];
    public function coachRequisition() {
        return $this->belongsTo(CoachRequisition::class);
    }
}
