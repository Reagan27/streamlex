<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldActivityTransportLog;
use App\Http\Resources\FieldActivityTransportLogResource;

class FieldActivityTransportLogController extends Controller
{
    public function index(Request $request)
    {
        $transportLogs = FieldActivityTransportLog::paginate(20);
        return FieldActivityTransportLogResource::collection($transportLogs);
    }

    public function show($id)
    {
        $transportLog = FieldActivityTransportLog::findOrFail($id);
        return new FieldActivityTransportLogResource($transportLog);
    }

    public function store(Request $request)
    {
        $transportLog = FieldActivityTransportLog::create($request->all());
        return new FieldActivityTransportLogResource($transportLog);
    }

    public function update(Request $request, $id)
    {
        $transportLog = FieldActivityTransportLog::findOrFail($id);
        $transportLog->update($request->all());
        return new FieldActivityTransportLogResource($transportLog);
    }

    public function destroy($id)
    {
        $transportLog = FieldActivityTransportLog::findOrFail($id);
        $transportLog->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
