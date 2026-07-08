<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionProposedCoach extends Model
{
    protected $fillable = [
        'coach_requisition_id', 'full_name', 'phone_number', 'email_address', 'sub_county_assigned', 'justification', 'roles_responsibilities'
    ];
    public function coachRequisition() {
        return $this->belongsTo(CoachRequisition::class);
    }
}
