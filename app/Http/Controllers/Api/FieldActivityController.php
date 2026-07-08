<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFieldActivityRequest;
use App\Http\Requests\UpdateFieldActivityRequest;
use App\Models\FieldActivity;
use App\Http\Resources\FieldActivityResource;
use Illuminate\Support\Facades\Validator;

class FieldActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = FieldActivity::with(['expenses', 'actualExpenses', 'logistics', 'transportLogs', 'timeline', 'documents', 'approvals', 'coachRequisition'])
            ->whereHas('coachRequisition', function($q) {
                $q->where('status', 'approved');
            })
            ->paginate(20);
        return FieldActivityResource::collection($activities);
    }

    public function show($id)
    {
        $activity = FieldActivity::with(['expenses', 'actualExpenses', 'logistics', 'transportLogs', 'timeline', 'documents', 'approvals'])->findOrFail($id);
        return new FieldActivityResource($activity);
    }

    public function store(StoreFieldActivityRequest $request)
    {
        $activity = FieldActivity::create($request->validated());
        return response()->json([
            'success' => true,
            'data' => new FieldActivityResource($activity)
        ]);
    }

    public function update(UpdateFieldActivityRequest $request, $id)
    {
        $activity = FieldActivity::findOrFail($id);
        $activity->update($request->validated());
        return response()->json([
            'success' => true,
            'data' => new FieldActivityResource($activity)
        ]);
    }

    public function destroy($id)
    {
        $activity = FieldActivity::findOrFail($id);
        $activity->delete();
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }

    // Set status (approve, reject, pay)
    public function setStatus(Request $request, $id)
    {
        $activity = FieldActivity::findOrFail($id);
        $request->validate([
            'status' => 'required|in:approved,rejected,funded',
        ]);
        $activity->status = $request->status;
        $activity->save();
        return response()->json([
            'success' => true,
            'data' => new FieldActivityResource($activity)
        ]);
    }
}
