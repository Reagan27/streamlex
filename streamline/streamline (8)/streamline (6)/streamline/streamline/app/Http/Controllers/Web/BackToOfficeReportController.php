<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BackToOfficeReport;
use Illuminate\Support\Facades\App;

class BackToOfficeReportController extends Controller
{
    public function index()
    {
        $reports = BackToOfficeReport::all();
        return view('back-to-office-reports.index', compact('reports'));
    }

    public function create()
    {
        return view('back-to-office-reports.create');
    }

    public function store(Request $request)
    {
        $report = BackToOfficeReport::create($request->all());
        return redirect()->route('back-to-office-reports.index');
    }

    public function show(BackToOfficeReport $report)
    {
        return view('back-to-office-reports.show', compact('report'));
    }

    public function edit(BackToOfficeReport $report)
    {
        return view('back-to-office-reports.edit', compact('report'));
    }

    public function update(Request $request, BackToOfficeReport $report)
    {
        $report->update($request->all());
        return redirect()->route('back-to-office-reports.index');
    }

    public function destroy(BackToOfficeReport $report)
    {
        $report->delete();
        return redirect()->route('back-to-office-reports.index');
    }

    public function exportPdf(BackToOfficeReport $report)
    {
        $report->load(['creator', 'approver', 'county', 'attachments']);

        // Use DomPDF or Snappy (Laravel package) for PDF generation
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('back-to-office-reports.pdf', compact('report'));
        return $pdf->download('BackToOfficeReport_' . ($report->project_name ?? $report->id) . '.pdf');
    }
}