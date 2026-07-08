<?php
namespace App\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldActivityExpense;
use App\Http\Resources\FieldActivityExpenseResource;

class FieldActivityExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = FieldActivityExpense::paginate(20);
        return FieldActivityExpenseResource::collection($expenses);
    }

    public function show($id)
    {
        $expense = FieldActivityExpense::findOrFail($id);
        return new FieldActivityExpenseResource($expense);
    }

    public function store(Request $request)
    {
        $expense = FieldActivityExpense::create($request->all());
        return response()->json([
            'success' => true,
            'data' => new FieldActivityExpenseResource($expense)
        ]);
    }

    public function update(Request $request, $id)
    {
        $expense = FieldActivityExpense::findOrFail($id);
        $expense->update($request->all());
        return response()->json([
            'success' => true,
            'data' => new FieldActivityExpenseResource($expense)
        ]);
    }

    public function destroy($id)
    {
        $expense = FieldActivityExpense::findOrFail($id);
        $expense->delete();
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
