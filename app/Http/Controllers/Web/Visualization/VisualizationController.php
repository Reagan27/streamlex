<?php

namespace Vanguard\Http\Controllers\Web\Visualization;

use Vanguard\Http\Controllers\Controller;
use Vanguard\ExcelData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Imports\ExcelDataImport;
use Illuminate\Support\Facades\DB;

class VisualizationController extends Controller
{
    public function index()
    {
        $excelData = ExcelData::select('file_identifier', 'file_type', 'original_filename')
            ->selectRaw('MAX(CountyName) as CountyName')
            ->selectRaw('COUNT(*) as record_count')
            ->groupBy('file_identifier', 'file_type', 'original_filename')
            ->get();

        return view('visualization.index', compact('excelData'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
            'file_type' => 'required|in:county_specific,multi_county'
        ]);

        $file = $request->file('excel_file');
        $fileType = $request->input('file_type');
        $originalFilename = $file->getClientOriginalName();

        try {
            // Read the Excel file to get the first data row
            $rows = Excel::toArray([], $file);
            $firstRow = $rows[0][1] ?? null; // Get the first data row (after headers)
            
            // Format date as ddmmyyyy
            $date = now()->format('dmY');
            
            // Generate file identifier based on type
            if ($fileType === 'county_specific' && $firstRow) {
                $countyName = isset($firstRow['countyname']) 
                    ? ucfirst(strtolower(trim($firstRow['countyname']))) 
                    : 'Unknown';
                $fileIdentifier = "{$countyName}_{$date}";
            } else {
                $fileIdentifier = "MultiCounty_{$date}";
            }

            // Import the data
            Excel::import(
                new ExcelDataImport($fileType, $fileIdentifier, $originalFilename), 
                $file
            );
            
            return redirect()->back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to import file: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function deleteFile(Request $request)
    {
        $fileIdentifier = $request->input('file_identifier');
        
        if ($fileIdentifier) {
            ExcelData::where('file_identifier', $fileIdentifier)->delete();
            return redirect()->back()->with('success', 'File and all associated records deleted successfully.');
        }

        return redirect()->back()->with('error', 'No file specified for deletion.');
    }
}
