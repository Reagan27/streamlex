<?php
namespace Vanguard\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Vanguard\ExcelData;
use Illuminate\Http\Request;

class ExcelDataController extends Controller
{
    public function index()
    {
        $data = ExcelData::select(
                'file_identifier', 
                'file_type', 
                'original_filename'
            )
            ->selectRaw('MAX(CountyName) as CountyName')
            ->selectRaw('COUNT(*) as record_count')
            ->groupBy('file_identifier', 'file_type', 'original_filename')
            ->get()
            ->map(function($item) {
                // Use original_filename if available, fall back to file_identifier
                $item->display_name = $item->original_filename ?? $item->file_identifier;
                return $item;
            });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function show($identifier)
    {
        // Try to find by original_filename first, then by file_identifier
        $data = ExcelData::where('original_filename', $identifier)
            ->orWhere('file_identifier', $identifier)
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found for the specified file: ' . $identifier
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // Optional: Add a method to handle file lookup by both identifiers
    public function lookup(Request $request)
    {
        $originalFilename = $request->input('original_filename');
        $fileIdentifier = $request->input('file_identifier');

        $query = ExcelData::query();

        if ($originalFilename) {
            $query->where('original_filename', $originalFilename);
        }

        if ($fileIdentifier) {
            $query->orWhere('file_identifier', $fileIdentifier);
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found for the specified criteria'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}