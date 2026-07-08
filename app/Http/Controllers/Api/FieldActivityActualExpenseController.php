<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldActivityActualExpense;
use App\Http\Resources\FieldActivityActualExpenseResource;

class FieldActivityActualExpenseController extends Controller
{
    public function index(Request $request)
    {
        $actualExpenses = FieldActivityActualExpense::paginate(20);
        return FieldActivityActualExpenseResource::collection($actualExpenses);
    }

    public function show($id)
    {
        $actualExpense = FieldActivityActualExpense::findOrFail($id);
        return new FieldActivityActualExpenseResource($actualExpense);
    }

    public function store(Request $request)
    {
        $actualExpense = FieldActivityActualExpense::create($request->all());
        return new FieldActivityActualExpenseResource($actualExpense);
    }

    public function update(Request $request, $id)
    {
        $actualExpense = FieldActivityActualExpense::findOrFail($id);
        $actualExpense->update($request->all());
        return new FieldActivityActualExpenseResource($actualExpense);
    }

    public function destroy($id)
    {
        $actualExpense = FieldActivityActualExpense::findOrFail($id);
        $actualExpense->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
