<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldActivityApproval;
use App\Http\Resources\FieldActivityApprovalResource;

class FieldActivityApprovalController extends Controller
{
    public function index(Request $request)
    {
        $approvals = FieldActivityApproval::paginate(20);
        return FieldActivityApprovalResource::collection($approvals);
    }

    public function show($id)
    {
        $approval = FieldActivityApproval::findOrFail($id);
        return new FieldActivityApprovalResource($approval);
    }

    public function store(Request $request)
    {
        $approval = FieldActivityApproval::create($request->all());
        return new FieldActivityApprovalResource($approval);
    }

    public function update(Request $request, $id)
    {
        $approval = FieldActivityApproval::findOrFail($id);
        $approval->update($request->all());
        return new FieldActivityApprovalResource($approval);
    }

    public function destroy($id)
    {
        $approval = FieldActivityApproval::findOrFail($id);
        $approval->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
