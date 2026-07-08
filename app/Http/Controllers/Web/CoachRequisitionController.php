<?php
namespace App\Http\Controllers\Web;

use App\Models\CoachRequisition;
use Vanguard\User;
use App\Models\County;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Vanguard\Http\Controllers\Controller;

class CoachRequisitionController extends Controller
{
    // Roles that skip the approval chain entirely
    protected array $bypassApprovalRoles = ['Admin'];

    // Fixed sequential approval chain — order matters
    // These are ALSO the only roles that can approve (replaces Gate/policy)
   // Flat list — used only for "is this role even allowed to approve" checks
    protected array $approvalChain = [
        'Regional_Coordinator',
        'Manager',
        'Finance',
        'Admin',
    ];

    // Step 1 of the chain — must approve first
    protected string $firstApprovalRole = 'Regional_Coordinator';
    // Step 2 — ANY ONE of these approving closes the requisition
    protected array $finalApprovalRoles = ['Manager', 'Finance', 'Admin'];

    // ── CREATE ────────────────────────────────────────────────────────────────

    public function create()
    {
        $user        = Auth::user();
        $counties    = DB::table('counties')->get();
        $countyIds   = $this->getAccessibleCountyIds($user);

        $users = $countyIds->isNotEmpty()
            ? User::with('subcounty')->whereIn('county_id', $countyIds)->get()
            : collect();

        $supervisors = $countyIds->isNotEmpty()
            ? User::whereIn('county_id', $countyIds)
                ->whereHas('role', fn($q) => $q->where('name', 'County_Coordinator'))
                ->get()
            : collect();

        $roles = \Vanguard\Role::orderBy('display_name')->get();

        return view('coach-requisitions.create', compact('user', 'counties', 'users', 'supervisors', 'roles'));
    }

    // ── STORE ─────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        \Log::debug('REQ: Requisition create - raw input', $request->all());

        try {
            $validated = $request->validate($this->validationRules());
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::debug('REQ: Requisition create - validation failed', ['errors' => $e->errors()]);
            throw $e;
        }

        $creator = Auth::user();

        // Allow optional `requested_by` so a user can create a requisition for themselves.
        // Creating on behalf of another user is restricted to Admin/Manager.
        $requestedBy = $validated['requested_by'] ?? $creator->id;
        if ($requestedBy != $creator->id && !in_array($creator->role ? $creator->role->name : null, ['Admin', 'Manager'])) {
            return back()->with('error', 'You are not allowed to create requisitions on behalf of other users.')->withInput();
        }

        $requesterUser = \Vanguard\User::find($requestedBy);
        $requesterRoleName = $requesterUser && $requesterUser->role ? $requesterUser->role->name : null;
        $isBypassRole = in_array($requesterRoleName, $this->bypassApprovalRoles);

        $justificationErrors = $this->validateJustifications($validated);
        if (!empty($justificationErrors)) {
            return back()->withErrors(['justification' => $justificationErrors])->withInput();
        }

        $initialStatus = $isBypassRole ? 'approved' : 'pending';

        $requisition = CoachRequisition::create([
            'title'             => $validated['title'],
            'requested_by'      => $requestedBy,
            'position_title'    => $request->input('position_title'),
            'number_of_coaches' => $validated['number_of_coaches'],
            'start_date'        => $validated['start_date'],
            'end_date'          => $validated['end_date'],
            'work_arrangement'  => $validated['work_arrangement'],
            'reporting_to'      => $requesterUser->supervisor_id ?? $creator->supervisor_id ?? null,
            'county_id'         => $requesterUser->county_id ?? $creator->county_id,
            'justification'     => null,
            'budget_line'       => $validated['budget_line'] ?? null,
            'budget_code'       => $validated['budget_code'] ?? null,
            'monthly_cost'      => $validated['monthly_cost'] ?? null,
            'total_cost'        => $validated['total_cost'] ?? null,
            'engagement_type'   => $validated['engagement_type'],
            'engagement_rate'   => $validated['engagement_rate'],
            'engagement_total'  => $validated['engagement_total'],
            'status'            => $initialStatus,
        ]);

        foreach ($validated['coach_full_name'] as $i => $name) {
            $requisition->proposedCoaches()->create([
                'coach_user_id'          => $request->input('coach_user_id')[$i] ?? null,
                'full_name'              => $name,
                'phone_number'           => $validated['coach_phone_number'][$i] ?? '',
                'email_address'          => $validated['coach_email_address'][$i] ?? '',
                'sub_county_assigned'    => $validated['coach_sub_county_assigned'][$i] ?? '',
                'roles_responsibilities' => $validated['roles_responsibilities'][$i] ?? null,
                'justification'          => $this->buildJustificationString($validated, $i),
            ]);
        }

        if ($isBypassRole) {
            $requisition->load('proposedCoaches');
            $this->createFieldActivities($requisition);
            return redirect()->route('coach-requisitions.index')
                ->with('success', 'Coach requisition submitted and field activities created successfully!');
        }

        $this->createApprovalChain($requisition);

        return redirect()->route('coach-requisitions.index')
            ->with('success', 'Coach requisition submitted successfully!');
    }

    // ── INDEX ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $user   = Auth::user();
        $search = request('search');
        $role   = $user->role ? $user->role->name : null;

        if (in_array($role, ['Admin', 'Manager', 'Finance'])) {
            $query = CoachRequisition::with(['user', 'approvals', 'fieldActivities'])
                ->orderBy('created_at', 'desc');

        } elseif ($role === 'Regional_Coordinator') {
            $countyIds = \Vanguard\RegionalCoordinatorCounty::where('user_id', $user->id)
                ->pluck('county_id');
            $query = CoachRequisition::with(['user', 'approvals', 'fieldActivities'])
                ->whereIn('county_id', $countyIds)
                ->orderBy('created_at', 'desc');

        } elseif ($role === 'County_Coordinator') {
            $query = CoachRequisition::with(['user', 'approvals', 'fieldActivities'])
                ->where('county_id', $user->county_id)
                ->orderBy('created_at', 'desc');

        } else {
            $query = CoachRequisition::with(['user', 'approvals', 'fieldActivities'])
                ->where(function ($q) use ($user) {
                    $q->where('county_id', $user->county_id)
                      ->orWhere('requested_by', $user->id);
                })
                ->orderBy('created_at', 'desc');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('first_name', 'like', "%$search%")
                       ->orWhere('last_name', 'like', "%$search%")
                       ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%$search%"]);
                })
                ->orWhere('title', 'like', "%$search%")
                ->orWhere('position_title', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
            });
        }

        $requisitions = $query->paginate(15);

        return view('coach-requisitions.index', compact('requisitions', 'search'));
    }

    // ── SHOW ──────────────────────────────────────────────────────────────────

     public function show($id)
    {
        $requisition = CoachRequisition::with(['proposedCoaches', 'approvals', 'user', 'fieldActivities'])
            ->findOrFail($id);

        $countyName = $requisition->county_id
            ? DB::table('counties')->where('id', $requisition->county_id)->value('name')
            : null;

        return view('coach-requisitions.show', compact('requisition', 'countyName'));
    }

    // ── EDIT ──────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $requisition = CoachRequisition::with(['proposedCoaches'])->findOrFail($id);

        // $user / $roleName MUST be resolved before the status check below,
        // otherwise $roleName is undefined (null) and the Admin bypass never
        // fires — every non-pending requisition gets blocked for everyone,
        // Admin included.
        $user     = Auth::user();
        $roleName = $user->role ? $user->role->name : null;

        if ($requisition->status !== 'pending' && $roleName !== 'Admin') {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'Only pending requisitions can be edited.');
        }

        if ($user->id !== $requisition->requested_by && !in_array($roleName, ['Admin', 'Manager'])) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'You do not have permission to edit this requisition.');
        }

        $counties  = DB::table('counties')->get();
        $countyIds = $this->getAccessibleCountyIds($user);

        $supervisors = $countyIds->isNotEmpty()
            ? User::whereIn('county_id', $countyIds)
                ->whereHas('role', fn($q) => $q->where('name', 'County_Coordinator'))
                ->get()
            : collect();

        $users = $countyIds->isNotEmpty()
            ? User::with('subcounty')->whereIn('county_id', $countyIds)->get()
            : collect();

        $roles = \Vanguard\Role::orderBy('display_name')->get();

        return view('coach-requisitions.edit', compact('requisition', 'user', 'counties', 'supervisors', 'users', 'roles'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $requisition = CoachRequisition::with(['proposedCoaches'])->findOrFail($id);

        // Same reordering fix as edit(): $user / $roleName must exist before
        // they're referenced in the status guard.
        $user     = Auth::user();
        $roleName = $user->role ? $user->role->name : null;

        if ($requisition->status !== 'pending' && $roleName !== 'Admin') {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'Only pending requisitions can be edited.');
        }

        if ($user->id !== $requisition->requested_by && !in_array($roleName, ['Admin', 'Manager'])) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'You do not have permission to edit this requisition.');
        }

        $validated = $request->validate($this->validationRules());

        $justificationErrors = $this->validateJustifications($validated);
        if (!empty($justificationErrors)) {
            return back()->withErrors(['justification' => $justificationErrors])->withInput();
        }

        $requisition->update([
            'title'             => $validated['title'],
            'position_title'    => $request->input('position_title'),
            'number_of_coaches' => $validated['number_of_coaches'],
            'start_date'        => $validated['start_date'],
            'end_date'          => $validated['end_date'],
            'work_arrangement'  => $validated['work_arrangement'],
            'budget_line'       => $validated['budget_line'] ?? null,
            'budget_code'       => $validated['budget_code'] ?? null,
            'monthly_cost'      => $validated['monthly_cost'] ?? null,
            'total_cost'        => $validated['total_cost'] ?? null,
            'engagement_type'   => $validated['engagement_type'],
            'engagement_rate'   => $validated['engagement_rate'],
            'engagement_total'  => $validated['engagement_total'],
        ]);

        $requisition->proposedCoaches()->delete();

        foreach ($validated['coach_full_name'] as $i => $name) {
            $requisition->proposedCoaches()->create([
                'coach_user_id'          => $request->input('coach_user_id')[$i] ?? null,
                'full_name'              => $name,
                'phone_number'           => $validated['coach_phone_number'][$i] ?? '',
                'email_address'          => $validated['coach_email_address'][$i] ?? '',
                'sub_county_assigned'    => $validated['coach_sub_county_assigned'][$i] ?? '',
                'roles_responsibilities' => $validated['roles_responsibilities'][$i] ?? null,
                'justification'          => $this->buildJustificationString($validated, $i),
            ]);
        }

        return redirect()->route('coach-requisitions.show', $id)
            ->with('success', 'Requisition updated successfully.');
    }

    // ── APPROVAL (view) ───────────────────────────────────────────────────────

    public function approval($id)
    {
        $user         = Auth::user();
        $userRoleName = $user->role ? $user->role->name : null;
       $requisition  = CoachRequisition::with(['approvals', 'user', 'proposedCoaches'])->findOrFail($id);
        $countyName   = $requisition->county_id
            ? DB::table('counties')->where('id', $requisition->county_id)->value('name')
            : null;

        if ($user->id === $requisition->requested_by) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'You cannot approve your own requisition.');
        }

        if (!in_array($userRoleName, $this->approvalChain)) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'You do not have permission to approve requisitions.');
        }

        $approval = $this->findPendingApprovalForUser($user, $requisition);

        if (!$approval) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'No pending approval for your role, or it is not yet your turn.');
        }

        return view('coach-requisitions.approval', compact('requisition', 'approval'));
    }

    // ── APPROVAL ACTION ───────────────────────────────────────────────────────

    public function approvalAction(Request $request, $id)
    {
        $user         = Auth::user();
        $userRoleName = $user->role ? $user->role->name : null;
        $requisition  = CoachRequisition::with(['approvals'])->findOrFail($id);

        if ($user->id === $requisition->requested_by) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'You cannot approve your own requisition.');
        }

        if (!in_array($userRoleName, $this->approvalChain)) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'You do not have permission to approve requisitions.');
        }

        $approval = $this->findPendingApprovalForUser($user, $requisition);

        if (!$approval) {
            return redirect()->route('coach-requisitions.show', $id)
                ->with('error', 'No pending approval for your role, or it is not yet your turn.');
        }

        $request->validate([
            'action'   => 'required|in:approved,not_approved',
            'comments' => 'nullable|string',
        ]);

        $approval->status           = $request->action === 'approved' ? 'approved' : 'not_approved';
        $approval->comments         = $request->comments;
        $approval->approver_name    = $user->first_name . ' ' . $user->last_name;
        $approval->approver_user_id = $user->id;
        $approval->approved_at      = now();
        $approval->save();

        $this->updateRequisitionStatus($requisition);

        return redirect()->route('coach-requisitions.show', $id)
            ->with('success', 'Approval action submitted.');
    }

    // ── PRIVATE HELPERS ───────────────────────────────────────────────────────

    /**
     * Resolve the county IDs a given user is allowed to act within when
     * populating coach/supervisor pickers on the create/edit forms.
     *
     * Most roles (Manager, Finance, Admin, County_Coordinator, regular users)
     * are tied to a single county via their own `county_id` column.
     *
     * Regional_Coordinator users are NOT tied to a single county — they are
     * assigned to multiple counties through the regional_coordinator_counties
     * pivot table (see RegionalCoordinatorCounty), the same way index() resolves
     * their accessible counties. Without this branch, $user->county_id is null
     * for Regional_Coordinators and the coach/supervisor lists silently come
     * back empty.
     */
    private function getAccessibleCountyIds($user)
    {
        $roleName = $user->role ? $user->role->name : null;

        if ($roleName === 'Regional_Coordinator') {
            return \Vanguard\RegionalCoordinatorCounty::where('user_id', $user->id)
                ->pluck('county_id');
        }

        return $user->county_id
            ? collect([$user->county_id])
            : collect();
    }

    private function findPendingApprovalForUser($user, $requisition)
{
    $userRoleName = $user->role ? $user->role->name : null;

    $minPendingLevel = $requisition->approvals()
        ->where('status', 'pending')
        ->min('approval_level');

    if ($minPendingLevel === null) {
        return null;
    }

    if ($userRoleName === 'Admin') {
        // Admin can act on whatever is currently pending at the active level
        return $requisition->approvals()
            ->where('approval_level', $minPendingLevel)
            ->where('status', 'pending')
            ->first();
    }

    return $requisition->approvals()
        ->where('approval_level', $minPendingLevel)
        ->where('status', 'pending')
        ->where('role', $userRoleName)
        ->first();
}

    private function updateRequisitionStatus($requisition)
{
    $rejectedCount = $requisition->approvals()->where('status', 'not_approved')->count();
    if ($rejectedCount > 0) {
        $requisition->status = 'not_approved';
        $requisition->save();
        return;
    }

    // Once one role at a level approves, the sibling rows at that same level
    // (e.g. Finance/Admin when Manager already approved) no longer block.
    $approvedLevels = $requisition->approvals()
        ->where('status', 'approved')
        ->pluck('approval_level')
        ->unique();

    foreach ($approvedLevels as $level) {
        $requisition->approvals()
            ->where('approval_level', $level)
            ->where('status', 'pending')
            ->update(['status' => 'skipped']);
    }

    $pendingCount  = $requisition->approvals()->where('status', 'pending')->count();
    $approvedCount = $requisition->approvals()->where('status', 'approved')->count();

    if ($pendingCount === 0) {
        $requisition->status = 'approved';
        $requisition->save();
        $requisition->load('proposedCoaches');
        $this->createFieldActivities($requisition);
        return;
    }

    if ($approvedCount > 0 && $pendingCount > 0) {
        $requisition->status = 'in_review';
        $requisition->save();
        return;
    }

    $requisition->status = 'pending';
    $requisition->save();
}
    private function createApprovalChain($requisition)
{
    $requesterUser     = \Vanguard\User::find($requisition->requested_by);
    $requesterRoleName = $requesterUser && $requesterUser->role ? $requesterUser->role->name : null;

    $level = 1;

    // Level 1: Regional_Coordinator (skip if the requester IS the regional coordinator)
    if ($requesterRoleName !== $this->firstApprovalRole) {
        $requisition->approvals()->create([
            'approval_level' => $level,
            'role'           => $this->firstApprovalRole,
            'status'         => 'pending',
        ]);
        $level++;
    }

    // Level 2: one row per eligible final role — first one to act wins
    foreach ($this->finalApprovalRoles as $role) {
        if ($role === $requesterRoleName) {
            continue; // requester can't approve their own requisition
        }
        $requisition->approvals()->create([
            'approval_level' => $level,
            'role'           => $role,
            'status'         => 'pending',
        ]);
    }
}

    private function createFieldActivities($requisition)
    {
        foreach ($requisition->proposedCoaches as $coach) {
            $coachUser = \Vanguard\User::where('email', $coach->email_address)
                ->orWhere('phone', $coach->phone_number)
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) = ?", [$coach->full_name])
                ->first();

            if (!$coachUser) {
                \Log::warning('Coach user not found for proposed coach', [
                    'full_name' => $coach->full_name,
                    'email'     => $coach->email_address,
                    'phone'     => $coach->phone_number,
                ]);
            }

            $createdBy     = $coachUser ? $coachUser->id : $requisition->requested_by;
            $fieldActivity = \App\Models\FieldActivity::create([
                'requisition_id'       => $requisition->id,
                'coach_requisition_id' => $requisition->id,
                'title'                => $requisition->title,
                'description'          => 'Activity for coach: ' . $coach->full_name . ' (' . $coach->email_address . ')',
                'start_date'           => $requisition->start_date,
                'end_date'             => $requisition->end_date,
                'status'               => 'draft',
                'created_by'           => $createdBy,
                'engagement_type'      => $requisition->engagement_type,
                'engagement_rate'      => $requisition->engagement_rate,
                'engagement_total'     => $requisition->engagement_total,
            ]);

            if ($coachUser && $fieldActivity) {
                try {
                    $coachUser->notify(new \App\Notifications\ActivityReminderNotification($fieldActivity));
                } catch (\Exception $e) {
                    \Log::error('REQ: Failed to send activity notification', ['error' => $e->getMessage()]);
                }
            }
        }
    }

    private function validationRules(): array
    {
        return [
            'requested_by'            => 'nullable|exists:users,id',
            'title'                    => 'required|string|max:255',
            'number_of_coaches'        => 'required|integer|min:1',
            'start_date'               => 'required|date',
            'end_date'                 => 'required|date|after_or_equal:start_date',
            'work_arrangement'         => 'required',
            'roles_responsibilities'   => 'nullable|array',
            'roles_responsibilities.*' => 'nullable|string',
            'justification'            => 'required|array',
            'justification.*'          => 'required|array',
            'justification_other'      => 'nullable|array',
            'justification_other.*'    => 'nullable|string',
            'coach_full_name'          => 'required|array',
            'coach_phone_number'       => 'required|array',
            'coach_email_address'      => 'required|array',
            'coach_sub_county_assigned'=> 'nullable|array',
            'budget_line'              => 'nullable|string',
            'budget_code'              => 'nullable|string',
            'monthly_cost'             => 'nullable|numeric',
            'total_cost'               => 'nullable|numeric',
            'engagement_type'          => 'required|string',
            'engagement_rate'          => 'required|numeric',
            'engagement_total'         => 'required|string',
        ];
    }

    private function validateJustifications(array $validated): array
    {
        $errors = [];
        foreach ($validated['coach_full_name'] as $i => $name) {
            $justifications = isset($validated['justification'][$i]) && is_array($validated['justification'][$i])
                ? array_filter($validated['justification'][$i], fn($v) => trim($v) !== '' && $v !== 'Other')
                : [];
            if (!count($justifications) && empty($validated['justification_other'][$i])) {
                $errors[$i] = "At least one justification is required for coach #" . ($i + 1) . ".";
            }
        }
        return $errors;
    }

    private function buildJustificationString(array $validated, int $i): ?string
    {
        $parts = [];
        if (!empty($validated['justification'][$i]) && is_array($validated['justification'][$i])) {
            $checked = array_filter($validated['justification'][$i], fn($v) => trim($v) !== '');
            if ($checked) {
                $parts[] = implode(', ', $checked);
            }
        }
        if (!empty($validated['justification_other'][$i])) {
            $parts[] = 'Other: ' . $validated['justification_other'][$i];
        }
        return $parts ? implode(', ', $parts) : null;
    }
}