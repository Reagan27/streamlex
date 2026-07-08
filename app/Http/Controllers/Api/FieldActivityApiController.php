<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\FieldActivity;
use App\Models\CoachRequisition;
use Vanguard\Http\Controllers\Controller;

class FieldActivityApiController extends Controller
{
    // List all activities
    public function index(Request $request)
    {
        $user = $request->user();
        $query = FieldActivity::with(['coachRequisition', 'user', 'approvals', 'expenses', 'transportLogs', 'documents']);

        if ($user->hasRole(['Admin', 'Manager', 'Finance'])) {
            // See all

        } elseif ($user->hasRole('Regional_Coordinator')) {
            $counties = $user->counties()->pluck('counties.id');
            // Show activities in their counties OR where they are a proposed coach
            $query->where(function($q) use ($user, $counties) {
                $q->whereHas('coachRequisition', function($q2) use ($counties) {
                    $q2->whereIn('county_id', $counties);
                })
                ->orWhereHas('coachRequisition', function($q2) use ($user) {
                    $q2->whereHas('proposedCoaches', function($q3) use ($user) {
                        $q3->where('email_address', $user->email)
                           ->orWhere('phone_number', $user->phone)
                           ->orWhereRaw(
                               "full_name = CONCAT(?, ' ', ?)",
                               [$user->first_name, $user->last_name]
                           );
                    });
                });
            });

        } elseif ($user->hasRole('County_Coordinator')) {
            // Show activities in their county OR where they are a proposed coach
            $query->where(function($q) use ($user) {
                $q->whereHas('coachRequisition', function($q2) use ($user) {
                    $q2->where('county_id', $user->county_id);
                })
                ->orWhereHas('coachRequisition', function($q2) use ($user) {
                    $q2->whereHas('proposedCoaches', function($q3) use ($user) {
                        $q3->where('email_address', $user->email)
                           ->orWhere('phone_number', $user->phone)
                           ->orWhereRaw(
                               "full_name = CONCAT(?, ' ', ?)",
                               [$user->first_name, $user->last_name]
                           );
                    });
                });
            });

        } elseif ($user->hasRole('Supervisor')) {
            $query->where(function($q) use ($user) {
                $q->whereHas('user', function($q2) use ($user) {
                    $q2->where('supervisor_id', $user->id);
                })
                ->orWhereHas('coachRequisition', function($q2) use ($user) {
                    $q2->whereHas('proposedCoaches', function($q3) use ($user) {
                        $q3->where('email_address', $user->email)
                           ->orWhere('phone_number', $user->phone)
                           ->orWhereRaw(
                               "full_name = CONCAT(?, ' ', ?)",
                               [$user->first_name, $user->last_name]
                           );
                    });
                });
            });

        } else {
            // ── FIX: show activities the user created OR was proposed as a coach for ──
            $query->where(function($q) use ($user) {
                // Activities directly created by this user
                $q->where('created_by', $user->id)
                  // OR activities linked to a requisition where they are a proposed coach
                  ->orWhereHas('coachRequisition', function($q2) use ($user) {
                      $q2->whereHas('proposedCoaches', function($q3) use ($user) {
                          $q3->where('email_address', $user->email)
                             ->orWhere('phone_number', $user->phone)
                             ->orWhereRaw(
                                 "full_name = CONCAT(?, ' ', ?)",
                                 [$user->first_name, $user->last_name]
                             );
                      });
                  });
            });
        }

        $activities = $query->orderByDesc('created_at')->get()->map(function ($activity) {
            $budget = (float)($activity->budget_programme ?? 0) + (float)($activity->budget_transport ?? 0);
            $engagement_type = $activity->engagement_type
                ?? ($activity->coachRequisition ? $activity->coachRequisition->engagement_type : null);
            $engagement_rate = $activity->engagement_rate
                ?? ($activity->coachRequisition ? $activity->coachRequisition->engagement_rate : null);
            $invoiceDoc = $activity->documents->where('file_type', 'invoice')->sortByDesc('created_at')->first();
            return [
                'id'                   => $activity->id,
                'title'                => $activity->title,
                'team'                 => $activity->team,
                'created_by'           => $activity->created_by,
                'created_by_name'      => $activity->user ? $activity->user->name : '',
                'start_date'           => $activity->start_date,
                'end_date'             => $activity->end_date,
                'status'               => $activity->status,
                'budget'               => $budget,
                'budget_programme'     => $activity->budget_programme,
                'budget_transport'     => $activity->budget_transport,
                'actual_spent'         => $activity->actual_spent,
                'disbursed'            => $activity->disbursed,
                'coach_requisition_id' => $activity->coach_requisition_id,
                'coach_requisition'    => $activity->coachRequisition ? $activity->coachRequisition->toArray() : null,
                'engagement_type'      => $engagement_type,
                'engagement_rate'      => (float) $engagement_rate,
                'approvals'            => $activity->approvals,
                'expenses'             => $activity->expenses,
                'transport'            => $activity->transportLogs,
                'location'             => $activity->location,
                'description'          => $activity->description,
                'invoice'              => $invoiceDoc ? [
                    'id' => $invoiceDoc->id,
                    'file_name' => $invoiceDoc->file_name,
                    'file_path' => $invoiceDoc->file_path,
                    'file_type' => $invoiceDoc->file_type,
                    'status' => 'uploaded'
                ] : null,
            ];
        });

        return response()->json($activities);
    }

    // Store a new activity
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'coach_requisition_id'         => 'nullable|exists:coach_requisitions,id',
            'title'                        => 'required|string|max:255',
            'description'                  => 'nullable|string',
            'team'                         => 'nullable|string|max:255',
            'location'                     => 'nullable|string|max:255',
            'start_date'                   => 'required|date',
            'end_date'                     => 'required|date|after_or_equal:start_date',
            'budget_programme'             => 'nullable|numeric',
            'budget_transport'             => 'nullable|numeric',
            'expenses'                     => 'nullable|array',
            'expenses.*.description'       => 'nullable|string|max:255',
            'expenses.*.category'          => 'nullable|string|max:255',
            'expenses.*.amount'            => 'nullable|numeric',
            'transport'                    => 'nullable|array',
            'transport.*.from_location'    => 'nullable|string|max:255',
            'transport.*.to_location'      => 'nullable|string|max:255',
            'transport.*.mode'             => 'nullable|string|max:255',
            'transport.*.planned_cost'     => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['created_by'] = $request->user()->id;
        $data['status']     = 'draft';

        $expenses  = $data['expenses'] ?? [];
        $transport = $data['transport'] ?? [];
        unset($data['expenses'], $data['transport']);

        $activity = FieldActivity::create($data);

        foreach ($expenses as $exp) {
            $activity->expenses()->create($exp);
        }
        foreach ($transport as $tr) {
            $activity->transportLogs()->create($tr);
        }

        $activity = $activity->load(['expenses', 'transportLogs', 'coachRequisition', 'user']);
        $budget = (float)($activity->budget_programme ?? 0) + (float)($activity->budget_transport ?? 0);
        $engagement_type = $activity->engagement_type
            ?? ($activity->coachRequisition ? $activity->coachRequisition->engagement_type : null);
        $engagement_rate = $activity->engagement_rate
            ?? ($activity->coachRequisition ? $activity->coachRequisition->engagement_rate : null);

        return response()->json([
            'activity' => [
                'id'                   => $activity->id,
                'title'                => $activity->title,
                'team'                 => $activity->team,
                'created_by'           => $activity->created_by,
                'created_by_name'      => $activity->user ? $activity->user->name : '',
                'start_date'           => $activity->start_date,
                'end_date'             => $activity->end_date,
                'status'               => $activity->status,
                'budget'               => $budget,
                'budget_programme'     => $activity->budget_programme,
                'budget_transport'     => $activity->budget_transport,
                'actual_spent'         => $activity->actual_spent,
                'disbursed'            => $activity->disbursed,
                'coach_requisition_id' => $activity->coach_requisition_id,
                'coach_requisition'    => $activity->coachRequisition ? $activity->coachRequisition->toArray() : null,
                'engagement_type'      => $engagement_type,
                'engagement_rate'      => (float) $engagement_rate,
                'expenses'             => $activity->expenses,
                'transport'            => $activity->transportLogs,
                'location'             => $activity->location,
                'description'          => $activity->description,
            ]
        ], 201);
    }

    // Show a single activity
    public function show($id)
    {
        $activity = FieldActivity::with(['coachRequisition', 'user'])->findOrFail($id);
        return response()->json(['activity' => $activity]);
    }

    // Update an activity
    public function update(Request $request, $id)
    {
        $activity = FieldActivity::findOrFail($id);
        $validated = $request->validate([
            'activity_type'  => 'sometimes|required|string|max:255',
            'description'    => 'sometimes|required|string',
            'start_time'     => 'sometimes|required',
            'end_time'       => 'sometimes|required',
            'outcomes'       => 'nullable|string',
            'resources_used' => 'nullable|string',
            'challenges'     => 'nullable|string',
        ]);
        $activity->update($validated);
        return response()->json(['activity' => $activity]);
    }

    // Delete an activity
    public function destroy($id)
    {
        $activity = FieldActivity::findOrFail($id);
        $activity->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // Get logs for a field activity
    // Get logs for a field activity
public function logs(Request $request, $id)
{
    $activity = FieldActivity::findOrFail($id);
    $user = $request->user();

    if (!$this->canAccessActivity($user, $activity)) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    // Use FieldActivityLog model directly to ensure consistent storage
    $logs = \App\Models\FieldActivityLog::where('field_activity_id', $activity->id)
        ->orderBy('date')
        ->get(['id', 'date', 'log_data']);

    return response()->json([
        'data' => $logs->map(fn($l) => [
            'date'     => \Carbon\Carbon::parse($l->date)->format('Y-m-d'),
            'log_data' => $l->log_data ?? [],
        ])
    ]);
}

// Store or update a log entry for a specific date
public function storeLogs(Request $request, $id)
{
    $activity = FieldActivity::findOrFail($id);
    $user = $request->user();

    if (!$this->canAccessActivity($user, $activity)) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'date'     => 'required|date_format:Y-m-d',
        'log_data' => 'required|array',
    ]);

    // Merge incoming docs with existing docs to avoid overwriting previous uploads
    $existingLog = \App\Models\FieldActivityLog::where('field_activity_id', $activity->id)
        ->where('date', $data['date'])
        ->first();

    $incoming = $data['log_data'];
    $existing = $existingLog ? ($existingLog->log_data ?? []) : [];

    // Ensure docs arrays exist
    $incomingDocs = isset($incoming['docs']) && is_array($incoming['docs']) ? $incoming['docs'] : [];
    $existingDocs = isset($existing['docs']) && is_array($existing['docs']) ? $existing['docs'] : [];

    // Merge by unique identifier (prefer 'id', fallback to 'file_path' or full item)
    $map = [];
    foreach ($existingDocs as $d) {
        $key = $d['id'] ?? ($d['file_path'] ?? json_encode($d));
        $map[$key] = $d;
    }
    foreach ($incomingDocs as $d) {
        $key = $d['id'] ?? ($d['file_path'] ?? json_encode($d));
        $map[$key] = $d;
    }
    $mergedDocs = array_values($map);

    // Build merged log_data
    $mergedLogData = $incoming;
    $mergedLogData['docs'] = $mergedDocs;

    // Always write to FieldActivityLog for consistency across all roles
    $log = \App\Models\FieldActivityLog::updateOrCreate(
        [
            'field_activity_id' => $activity->id,
            'date'              => $data['date'],
        ],
        [
            'log_data' => $mergedLogData,
        ]
    );

    return response()->json([
        'success' => true,
        'data' => [
            'date'     => \Carbon\Carbon::parse($log->date)->format('Y-m-d'),
            'log_data' => $log->log_data,
        ],
    ]);
}

    // Set status (Admin/Manager/Finance only)
    public function setStatus(Request $request, $id)
    {
        $activity = FieldActivity::findOrFail($id);
        $user = $request->user();

        if (!$user->hasRole(['Admin', 'Manager', 'Finance'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'status' => 'required|in:draft,approved,funded,rejected,in_review,pending',
        ]);

        $activity->status = $data['status'];
        $activity->save();

        return response()->json(['success' => true, 'data' => $activity]);
    }

    // Helper: authorization check
    // ── FIX: also allow proposed coaches to access logs ──
    private function canAccessActivity($user, $activity): bool
    {
        if ($user->hasRole(['Admin', 'Manager', 'Finance', 'Regional_Coordinator'])) {
            return true;
        }

        if ($activity->created_by == $user->id) {
            return true;
        }

        // Check if user is a proposed coach on the linked requisition
        if ($activity->coach_requisition_id) {
            return \App\Models\RequisitionProposedCoach::where('coach_requisition_id', $activity->coach_requisition_id)
                ->where(function($q) use ($user) {
                    $q->where('email_address', $user->email)
                      ->orWhere('phone_number', $user->phone ?? $user->phone_number ?? '')
                      ->orWhereRaw(
                          "full_name = CONCAT(?, ' ', ?)",
                          [$user->first_name, $user->last_name]
                      );
                })->exists();
        }

        return false;
    }
}