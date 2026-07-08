<?php

namespace Vanguard\Console\Commands;

use App\Models\CoachRequisition;
use App\Models\RequisitionApproval;
use Illuminate\Console\Command;

class FixRequisitionApprovals extends Command
{
    protected $signature = 'fix:requisition-approvals';
    protected $description = 'Rebuild approval chains for all requisitions';

    public function handle()
    {
        $approvalChain = [
            'County_Coordinator',
            'Regional_Coordinator',
            'Manager',
            'Finance',
            'Admin',
        ];

        $requisitions = CoachRequisition::whereIn('status', ['pending', 'in_review'])->get();

        foreach ($requisitions as $req) {
            $requester = \Vanguard\User::find($req->requested_by);
            $requesterRoleName = $requester && $requester->role ? $requester->role->name : null;

            if (!$requesterRoleName) {
                $this->warn("Requisition {$req->id}: requester role not found");
                continue;
            }

            // Find requester's position in chain
            $requesterPosition = array_search($requesterRoleName, $approvalChain);
            if ($requesterPosition === false) {
                $this->warn("Requisition {$req->id}: requester role '{$requesterRoleName}' not in chain");
                continue;
            }

            // Delete old approvals
            $req->approvals()->delete();

            // Create new approvals starting from next role
            $approvalLevel = 1;
            for ($i = $requesterPosition + 1; $i < count($approvalChain); $i++) {
                $req->approvals()->create([
                    'approval_level' => $approvalLevel,
                    'role'           => $approvalChain[$i],
                    'status'         => 'pending',
                ]);
                $approvalLevel++;
            }

            $this->info("Requisition {$req->id} (by {$requesterRoleName}): rebuilt approval chain");
        }

        $this->info('Done!');
    }
}
