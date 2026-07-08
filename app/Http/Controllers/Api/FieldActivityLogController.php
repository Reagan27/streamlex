<?php

namespace Vanguard\Http\Controllers\Api;

use App\Models\FieldActivity;
use App\Models\FieldActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vanguard\Http\Controllers\Controller;

class FieldActivityLogController extends Controller
{
    /**
     * Get all logs for a field activity.
     * Admins/Managers/Finance/Regional Coordinators see all;
     * other users only see logs for activities they can access.
     */
    public function index(Request $request, $activityId)
    {
        $user = $request->user();
        $activity = FieldActivity::findOrFail($activityId);

        if (!$this->canAccess($user, $activity)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $logs = FieldActivityLog::where('field_activity_id', $activityId)
            ->orderBy('date')
            ->get(['id', 'date', 'log_data']);

        return response()->json([
            'success' => true,
            'data' => $logs->map(fn ($log) => [
                'date' => \Carbon\Carbon::parse($log->date)->format('Y-m-d'),
                'log_data' => $log->log_data ?? [],
            ]),
        ]);
    }

    /**
     * Store or update a log for a specific day.
     */
    public function store(Request $request, $activityId)
    {
        $user = $request->user();
        $activity = FieldActivity::findOrFail($activityId);

        if (!$this->canAccess($user, $activity)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'log_data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $log = FieldActivityLog::updateOrCreate(
            [
                'field_activity_id' => $activityId,
                'date' => $request->date,
            ],
            [
                'log_data' => $request->log_data,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'date' => \Carbon\Carbon::parse($log->date)->format('Y-m-d'),
                'log_data' => $log->log_data,
            ],
        ]);
    }

    /**
     * Approve or reject a specific day's log entry.
     * Admin / Manager / Finance only.
     */
    public function review(Request $request, $activityId, $date)
    {
        $user = $request->user();

        if (!$this->canReview($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        FieldActivity::findOrFail($activityId);

        $data = $request->validate([
            'status' => 'required|in:approved,rejected',
            'note' => 'nullable|string|max:500',
        ]);

        $log = FieldActivityLog::where('field_activity_id', $activityId)
            ->where('date', $date)
            ->firstOrFail();

        $logData = $log->log_data ?? [];
        $logData['review'] = [
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'by' => $user->name,
            'at' => now()->toISOString(),
        ];

        $log->log_data = $logData;
        $log->save();

        return response()->json([
            'success' => true,
            'date' => \Carbon\Carbon::parse($log->date)->format('Y-m-d'),
            'log_data' => $log->log_data,
        ]);
    }

    /**
     * Approve or reject a specific work item in a day's log entry.
     * Admin / Manager / Finance only.
     */
    public function reviewWork(Request $request, $activityId, $date, $workIndex)
    {
        return $this->reviewItem($request, $activityId, $date, 'work', $workIndex);
    }

    /**
     * Approve or reject a specific item in work/expenses/logistics.
     * Admin / Manager / Finance only.
     */
    public function reviewItem(Request $request, $activityId, $date, $type, $itemIndex)
    {
        $user = $request->user();

        if (!$this->canReview($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if (!in_array($type, ['work', 'expenses', 'logistics'], true)) {
            return response()->json(['message' => 'Invalid review type'], 422);
        }

        FieldActivity::findOrFail($activityId);

        $data = $request->validate([
            'status' => 'required|in:approved,rejected',
            'note' => 'nullable|string|max:500',
        ]);

        $log = FieldActivityLog::where('field_activity_id', $activityId)
            ->where('date', $date)
            ->firstOrFail();

        $logData = $log->log_data ?? [];
        $items = $logData[$type] ?? [];

        if (!isset($items[$itemIndex])) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Work items can be stored as plain strings; normalize before adding review metadata.
        if ($type === 'work' && !is_array($items[$itemIndex])) {
            $items[$itemIndex] = ['desc' => (string) $items[$itemIndex]];
        }

        $items[$itemIndex]['review'] = [
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'by' => $user->name,
            'at' => now()->toISOString(),
        ];

        $logData[$type] = $items;
        $log->log_data = $logData;
        $log->save();

        return response()->json([
            'success' => true,
            'date' => \Carbon\Carbon::parse($log->date)->format('Y-m-d'),
            'type' => $type,
            'item_index' => (int) $itemIndex,
            'log_data' => $log->log_data,
            'review' => $items[$itemIndex]['review'],
        ]);
    }

    /**
     * Shared authorization logic for viewing/editing logs.
     */
    private function canAccess($user, FieldActivity $activity): bool
    {
        if ($user->hasRole(['Admin', 'Manager', 'Finance', 'Regional_Coordinator'])) {
            return true;
        }

        if ((int) $activity->created_by === (int) $user->id) {
            return true;
        }

        if ($activity->coach_requisition_id) {
            return \App\Models\RequisitionProposedCoach::where('coach_requisition_id', $activity->coach_requisition_id)
                ->where(function ($query) use ($user) {
                    $query->where('email_address', $user->email)
                        ->orWhere('phone_number', $user->phone ?? $user->phone_number ?? '')
                        ->orWhereRaw("full_name = CONCAT(?, ' ', ?)", [$user->first_name, $user->last_name]);
                })->exists();
        }

        return false;
    }

    private function canReview($user): bool
    {
        return $user && $user->hasRole(['Admin', 'Manager', 'Finance']);
    }
}
