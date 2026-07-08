<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldActivityLogistic;
use App\Http\Resources\FieldActivityLogisticResource;

class FieldActivityLogisticController extends Controller
{
    public function index(Request $request)
    {
        $logistics = FieldActivityLogistic::paginate(20);
        return FieldActivityLogisticResource::collection($logistics);
    }

    public function show($id)
    {
        $logistic = FieldActivityLogistic::findOrFail($id);
        return new FieldActivityLogisticResource($logistic);
    }

    public function store(Request $request)
    {
        $logistic = FieldActivityLogistic::create($request->all());
        return new FieldActivityLogisticResource($logistic);
    }

    public function update(Request $request, $id)
    {
        $logistic = FieldActivityLogistic::findOrFail($id);
        $logistic->update($request->all());
        return new FieldActivityLogisticResource($logistic);
    }

    public function destroy($id)
    {
        $logistic = FieldActivityLogistic::findOrFail($id);
        $logistic->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
