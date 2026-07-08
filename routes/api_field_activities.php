<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Vanguard\FieldReport;
use Vanguard\FieldReportActivity;

// API endpoint for fam.blade.php to fetch field activities
Route::middleware(['auth:sanctum'])->get('/field-activities', function (Request $request) {
    // You can add filters here if needed (e.g., by user, county, etc.)
    $activities = FieldReportActivity::with(['report', 'report.county', 'report.creator'])
        ->orderByDesc('created_at')
        ->limit(100)
        ->get();

    return response()->json([
        'activities' => $activities
    ]);
});
