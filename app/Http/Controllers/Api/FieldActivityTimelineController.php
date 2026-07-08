<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldActivityTimeline;
use App\Http\Resources\FieldActivityTimelineResource;

class FieldActivityTimelineController extends Controller
{
    public function index(Request $request)
    {
        $timelines = FieldActivityTimeline::paginate(20);
        return FieldActivityTimelineResource::collection($timelines);
    }

    public function show($id)
    {
        $timeline = FieldActivityTimeline::findOrFail($id);
        return new FieldActivityTimelineResource($timeline);
    }

    public function store(Request $request)
    {
        $timeline = FieldActivityTimeline::create($request->all());
        return new FieldActivityTimelineResource($timeline);
    }

    public function update(Request $request, $id)
    {
        $timeline = FieldActivityTimeline::findOrFail($id);
        $timeline->update($request->all());
        return new FieldActivityTimelineResource($timeline);
    }

    public function destroy($id)
    {
        $timeline = FieldActivityTimeline::findOrFail($id);
        $timeline->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
