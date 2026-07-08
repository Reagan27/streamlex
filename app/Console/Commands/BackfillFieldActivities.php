<?php
namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CoachRequisition;
use App\Models\FieldActivity;
use Vanguard\User;

class BackfillFieldActivities extends Command
{
    protected $signature = 'field-activities:backfill';
    protected $description = 'Backfill FieldActivity records for approved coach requisitions without activities';

    public function handle()
    {
        $requisitions = CoachRequisition::with(['proposedCoaches'])
            ->where('status', 'approved')
            ->get();
        $created = 0;
        foreach ($requisitions as $requisition) {
            foreach ($requisition->proposedCoaches as $coach) {
                $user = User::where('email', $coach->email_address)->first();
                $createdBy = $user ? $user->id : $requisition->requested_by;
                $exists = FieldActivity::where('coach_requisition_id', $requisition->id)
                    ->where('created_by', $createdBy)
                    ->exists();
                if (!$exists) {
                    $title = $requisition->title;
                    if (empty($title)) {
                        $title = $requisition->position_title ?: 'Coach Activity';
                    }
                    FieldActivity::create([
                        'requisition_id' => $requisition->id,
                        'coach_requisition_id' => $requisition->id,
                        'title' => $title,
                        'description' => 'Activity for coach: ' . $coach->full_name . ' (' . $coach->email_address . ')',
                        'start_date' => $requisition->start_date,
                        'end_date' => $requisition->end_date,
                        'status' => 'draft',
                        'created_by' => $createdBy,
                    ]);
                    $created++;
                }
            }
        }
        $this->info("Backfill complete. $created activities created.");
    }
}
