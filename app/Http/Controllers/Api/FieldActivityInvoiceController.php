<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class FieldActivityInvoiceController extends Controller
{
    public function store(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Invoice creation via /api/field-activities/{id}/invoice is not implemented. Use the field activity document upload endpoint instead.'
        ], 501);
    }

    public function review(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Invoice review is not implemented on this endpoint.'
        ], 501);
    }

    public function show($id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Invoice retrieval is not implemented on this endpoint.'
        ], 501);
    }
}
