<?php

namespace Vanguard\Http\Controllers\Web\Visualization;

use Vanguard\Imports\ExcelDataImport;
use Illuminate\Http\Request;
use Vanguard\ExcelData;
use Vanguard\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller 
{
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
            'file_type' => 'required|in:county_specific,multi_county'
        ]);

        $file = $request->file('excel_file');
        $fileType = $request->input('file_type');
        $originalFilename = $file->getClientOriginalName();

        // Generate unique file identifier
        $date = now()->format('Y-m-d');
        $fileIdentifier = $date . '_' . uniqid();

        try {
            Excel::import(new ExcelDataImport($fileType, $fileIdentifier, $originalFilename), $file);
            return redirect()->back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to import file: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function deleteSelected(Request $request)
    {
        $ids = $request->input('selected', []);
        ExcelData::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Selected entries deleted successfully.');
    }
}