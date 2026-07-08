<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Vanguard\Role;
use App\Models\CoachRequisition;
use Illuminate\Support\Facades\DB;

echo "ROLE NAMES:\n";
foreach (Role::pluck('name') as $name) {
    echo "- $name\n";
}

echo "\nLATEST COACH REQUISITION APPROVALS:\n";
$r = CoachRequisition::with('approvals')->latest()->first();
if (!$r) {
    echo "no requisition found\n";
    exit(0);
}
echo "Requisition {$r->id} status={$r->status}\n";
foreach ($r->approvals as $a) {
    echo "- lvl{$a->approval_level} role={$a->role} status={$a->status} approved_by=" . ($a->approver_user_id ?? 'null') . " approved_at=" . ($a->approved_at ?? 'null') . "\n";
}

echo "\nAPPROVALS TABLE ROWS FOR THIS REQUISITION:\n";
$rows = DB::table('requisition_approvals')->where('coach_requisition_id', $r->id)->orderBy('approval_level')->get();
foreach ($rows as $row) {
    echo "- id={$row->id} level={$row->approval_level} role={$row->role} status={$row->status} approved_by={$row->approved_by} approver_user_id={$row->approver_user_id}\n";
}
