<?php
namespace App\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\FieldActivity;
use App\Models\FieldActivityExpense;
use App\Models\FieldActivityTransport;
use App\Models\FieldActivityDocument;
use App\Models\FieldActivityApproval;
use App\Models\CoachRequisition;
use App\Models\User;

class FieldActivityController extends Controller
{
    // index()
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('field-activities.view')) {
            abort(403, 'You do not have permission to view field activities.');
        }
        $query = FieldActivity::query()->with(['expenses', 'transportLogs', 'documents', 'approvals', 'coachRequisition']);
        // Role-based scoping
        if ($user->hasRole(['Admin', 'Manager', 'Finance'])) {
            // See all
        } elseif ($user->hasRole('Regional_Coordinator')) {
            $counties = $user->counties()->pluck('id');
            $query->whereHas('coachRequisition', function($q) use ($counties) {
                $q->whereIn('county_id', $counties);
            });
        } else {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('coachRequisition', function($q2) use ($user) {
                      $q2->where('county_id', $user->county_id);
                  });
            });
        }
        // Filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                  ->orWhere('team', 'like', "%$search%") ;
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('requisition_id')) {
            $query->where('coach_requisition_id', $request->input('requisition_id'));
        }
        $activities = $query->orderByDesc('created_at')->paginate(20);
        $requisition = null;
        if ($request->filled('requisition_id')) {
            $requisition = CoachRequisition::find($request->input('requisition_id'));
        }
        $user = Auth::user()->load('role');
        return view('field-activities.index', compact('activities', 'requisition', 'user'));
    }

    // create()
    public function create(Request $request)
    {
        $requisition = null;
        if ($request->filled('requisition_id')) {
            $requisition = CoachRequisition::where('status', 'approved')->find($request->input('requisition_id'));
        }
        $teams = DB::table('teams')->pluck('name', 'id');
        $user = Auth::user()->load('role');
        return view('field-activities.create', compact('requisition', 'teams', 'user'));
    }

    // store()
    public function store(Request $request)
    {
        $data = $request->validate([
            'coach_requisition_id' => 'nullable|exists:coach_requisitions,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget_programme' => 'nullable|numeric',
            'budget_transport' => 'nullable|numeric',
        ]);
        $data['created_by'] = Auth::id();
        $data['status'] = 'draft';
        $activity = FieldActivity::create($data);
        // Expenses
        foreach ($request->input('expenses', []) as $exp) {
            $activity->expenses()->create($exp);
        }
        // Transport
        foreach ($request->input('transport', []) as $tr) {
            $activity->transportLogs()->create($tr);
        }
        return redirect()->route('field-activities.show', $activity->id);
    }

    // show()
    public function show($id)
    {
        $activity = FieldActivity::with(['expenses', 'transportLogs', 'documents', 'approvals', 'coachRequisition', 'timeline'])->findOrFail($id);
        $timeline = $activity->timeline()->orderByDesc('created_at')->get();
        $user = Auth::user();
        if (!$user->hasPermission('field-activities.view')) {
            abort(403, 'You do not have permission to view field activities.');
        }
        $allowed = false;
        // Admin, Manager, Finance, Regional_Coordinator can always access
        if ($user->hasRole(['Admin', 'Manager', 'Finance', 'Regional_Coordinator'])) {
            $allowed = true;
        } else {
            // Allow creator
            if ($activity->created_by == $user->id) {
                $allowed = true;
            } else if ($activity->coach_requisition_id) {
                // Check if user is a proposed coach for this requisition
                $proposed = \App\Models\RequisitionProposedCoach::where('coach_requisition_id', $activity->coach_requisition_id)
                    ->where(function($q) use ($user) {
                        $q->where('email_address', $user->email)
                          ->orWhere('phone_number', $user->phone_number)
                          ->orWhere('full_name', $user->name);
                    })->exists();
                if ($proposed) {
                    $allowed = true;
                }
            }
        }
        if (!$allowed) {
            abort(403, 'You are not authorized to access this activity.');
        }
        $user = Auth::user()->load('role');
        return view('field-activities.show', compact('activity', 'timeline', 'user'));
    }

    /**
     * Generate and return invoice PDF for a field activity
     */
    public function invoicePdf($id)
    {
        $activity = FieldActivity::with('logs', 'user')->findOrFail($id);

        $user = Auth::user();
        $allowed = false;
        if ($user->hasRole(['Admin', 'Manager', 'Finance', 'Regional_Coordinator'])) {
            $allowed = true;
        } else {
            if ($activity->created_by == $user->id) $allowed = true;
            else {
                $proposed = \App\Models\RequisitionProposedCoach::where('coach_requisition_id', $activity->coach_requisition_id)
                    ->where(function($q) use ($user) {
                        $q->where('email_address', $user->email)
                          ->orWhere('phone_number', $user->phone_number)
                          ->orWhere('full_name', $user->name);
                    })->exists();
                if ($proposed) $allowed = true;
            }
        }
        if (! $allowed) abort(403);

        // compute totals are handled by the Blade template, just render it
        $html = view('field-activities.invoice', compact('activity'))->render();

        try {
            $pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15,15,15,15]);
            $pdf->setDefaultFont('helvetica');
            $pdf->writeHTML($html);
            $out = $pdf->output('', 'S');
            $filename = 'FAM-invoice-' . str_pad($activity->id,5,'0',STR_PAD_LEFT) . '.pdf';
            return response($out, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    // update()
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasPermission('field-activities.edit')) {
            abort(403, 'You do not have permission to edit field activities.');
        }
        $activity = FieldActivity::findOrFail($id);
        if (!in_array($activity->status, ['draft', 'submitted'])) {
            abort(403);
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget_programme' => 'nullable|numeric',
            'budget_transport' => 'nullable|numeric',
        ]);
        $activity->update($data);
        // Sync expenses
        $activity->expenses()->delete();
        foreach ($request->input('expenses', []) as $exp) {
            $activity->expenses()->create($exp);
        }
        // Sync transport
        $activity->transportLogs()->delete();
        foreach ($request->input('transport', []) as $tr) {
            $activity->transportLogs()->create($tr);
        }
        return back()->with('success', 'Activity updated.');
    }

    // submit()
    public function submit($id)
    {
        $activity = FieldActivity::findOrFail($id);
        if ($activity->status !== 'draft') abort(403);
        $activity->status = 'pending';
        $activity->save();
        // Create approval chain (match CoachRequisition)
        $approvalChain = [
            ['level' => 1, 'role' => 'County_Coordinator'],
            ['level' => 2, 'role' => 'Regional_Coordinator'],
            ['level' => 3, 'role' => 'Manager'],
            ['level' => 4, 'role' => 'Finance'],
            ['level' => 5, 'role' => 'Admin'],
        ];
        foreach ($approvalChain as $step) {
            FieldActivityApproval::create([
                'field_activity_id' => $activity->id,
                'approval_level' => $step['level'],
                'role' => $step['role'],
                'status' => 'pending',
            ]);
        }
        return redirect()->route('field-activities.show', $activity->id)->with('success', 'Submitted for approval.');
    }

    // updateProgress()
    public function updateProgress(Request $request, $id)
    {
        $activity = FieldActivity::findOrFail($id);
        $data = $request->validate([
            'progress_pct' => 'required|integer|min:0|max:100',
            'note' => 'nullable|string',
        ]);
        $activity->progress_pct = $data['progress_pct'];
        if ($activity->status !== 'in_progress' && $activity->progress_pct > 0) {
            $activity->status = 'in_progress';
        }
        $activity->save();
        // Optionally log note as timeline (not implemented here)
        return response()->json(['success' => true, 'progress_pct' => $activity->progress_pct]);
    }

    // approval()
    public function approval($id)
    {
        $user = Auth::user();
        $activity = FieldActivity::with(['approvals', 'user'])->findOrFail($id);
        // Prevent creator from approving their own activity
        if ($user->id === $activity->created_by) {
            return redirect()->route('field-activities.show', $id)
                ->with('error', 'You cannot approve your own activity.');
        }
        if ($user->role && $user->role->name === 'Admin') {
            $pending = $activity->approvals()->where('status', 'pending')->first();
        } else {
            $userRoleName = $user->role->name ?? null;
            $pending = $activity->approvals()
                ->where('role', $userRoleName)
                ->where('status', 'pending')
                ->first();
        }
        if (!$pending) {
            return redirect()->route('field-activities.show', $id)
                ->with('error', 'No pending approval for your role.');
        }
        return view('field-activities.approval', compact('activity', 'pending'));
    }

    // approvalAction()
    public function approvalAction(Request $request, $id)
    {
        $user = Auth::user();
        $activity = FieldActivity::with(['approvals', 'user'])->findOrFail($id);
        if ($user->id === $activity->created_by) {
            return redirect()->route('field-activities.show', $id)
                ->with('error', 'You cannot approve your own activity.');
        }
        if ($user->role && $user->role->name === 'Admin') {
            $pending = $activity->approvals()->where('status', 'pending')->firstOrFail();
        } else {
            $userRoleName = $user->role->name ?? null;
            $pending = $activity->approvals()
                ->where('role', $userRoleName)
                ->where('status', 'pending')
                ->firstOrFail();
        }
        $request->validate([
            'action'   => 'required|in:approved,not_approved',
            'comments' => 'nullable|string',
        ]);
        $pending->status           = $request->action == 'approved' ? 'approved' : 'not_approved';
        $pending->comments         = $request->comments;
        $pending->approver_name    = $user->name;
        $pending->approver_user_id = $user->id;
        $pending->approved_at      = now();
        $pending->save();
        // Update activity status (match CoachRequisition logic)
        if ($pending->status == 'not_approved') {
            $activity->status = 'not_approved';
            $activity->save();
        } else {
            $pendingCount = $activity->approvals()->where('status', 'pending')->count();
            $approvedCount = $activity->approvals()->where('status', 'approved')->count();
            $noneRejected = $activity->approvals()->where('status', 'not_approved')->count() == 0;
            if ($pendingCount === 0 && $noneRejected) {
                $activity->status = 'approved';
                $activity->save();
            } else if ($approvedCount > 0 && $pendingCount > 0 && $noneRejected) {
                $activity->status = 'in_review';
                $activity->save();
            } else if ($pendingCount === $activity->approvals()->count()) {
                // All still pending
                $activity->status = 'pending';
                $activity->save();
            }
        }
        return redirect()->route('field-activities.show', $id)->with('success', 'Approval action submitted.');
    }

    // uploadDocument()
    public function uploadDocument(Request $request, $id)
    {
        $activity = FieldActivity::findOrFail($id);
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
            'type' => 'nullable|string',
        ]);
        $file = $request->file('file');
        $type = $request->input('type');
        $ext = $file->getClientOriginalExtension();
        $fileType = 'photo';
        if (in_array($ext, ['pdf', 'doc', 'docx'])) {
            $fileType = $type === 'receipt' ? 'receipt' : 'report';
        } elseif (in_array($ext, ['xls', 'xlsx'])) {
            $fileType = 'report';
        }
        $path = $file->storeAs("public/field-activities/{$activity->id}", $file->getClientOriginalName());
        $doc = $activity->documents()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $fileType,
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);
        return response()->json(['success' => true, 'file' => $doc]);
    }

    // addTransport()
    public function addTransport(Request $request, $id)
    {
        $activity = FieldActivity::findOrFail($id);
        $data = $request->validate([
            'from_location' => 'required|string',
            'to_location' => 'required|string',
            'mode' => 'required|string',
            'planned_cost' => 'required|numeric',
            'actual_cost' => 'nullable|numeric',
            'departure_lat' => 'nullable|numeric',
            'departure_lng' => 'nullable|numeric',
            'arrival_lat' => 'nullable|numeric',
            'arrival_lng' => 'nullable|numeric',
            'departure_time' => 'nullable|date',
            'arrival_time' => 'nullable|date',
            'gps_status' => 'nullable|in:pending,verified',
        ]);
        $tr = $activity->transportLogs()->create($data);
        return response()->json(['success' => true, 'transport' => $tr]);
    }

    // updateGPS()
    public function updateGPS(Request $request, $id, $transportId)
    {
        $activity = FieldActivity::findOrFail($id);
        $transport = $activity->transportLogs()->findOrFail($transportId);
        $data = $request->validate([
            'departure_lat' => 'nullable|numeric',
            'departure_lng' => 'nullable|numeric',
            'arrival_lat' => 'nullable|numeric',
            'arrival_lng' => 'nullable|numeric',
            'departure_time' => 'nullable|date',
            'arrival_time' => 'nullable|date',
        ]);
        $data['gps_status'] = 'verified';
        $transport->update($data);
        return response()->json(['success' => true]);
    }

    // reconcile()
    public function reconcile(Request $request, $id)
    {
        $activity = FieldActivity::with(['expenses', 'transportLogs'])->findOrFail($id);
        // Update actual_amount for expenses
        foreach ($request->input('expenses', []) as $eid => $actual) {
            $expense = $activity->expenses()->find($eid);
            if ($expense) {
                $expense->actual_amount = $actual;
                $expense->save();
            }
        }
        // Update actual_cost for transport
        foreach ($request->input('transport', []) as $tid => $actual) {
            $transport = $activity->transportLogs()->find($tid);
            if ($transport) {
                $transport->actual_cost = $actual;
                $transport->save();
            }
        }
        // Recalculate actual_spent
        $actualSpent = $activity->expenses()->sum('actual_amount') + $activity->transportLogs()->sum('actual_cost');
        $activity->actual_spent = $actualSpent;
        if (!in_array($activity->status, ['closed'])) {
            $activity->status = 'reconciling';
        }
        $activity->save();
        return back()->with('success', 'Reconciliation updated.');
    }

    // submitClosure()
    public function submitClosure($id)
    {
        $activity = FieldActivity::with('expenses')->findOrFail($id);
        // Ensure all actual_amounts are filled
        if ($activity->expenses()->whereNull('actual_amount')->count() > 0) {
            return back()->with('error', 'Fill all actual expense amounts before closing.');
        }
        $activity->status = 'closed';
        $activity->save();
        return redirect()->route('field-activities.index')->with('success', 'Activity closed.');
    }
}
