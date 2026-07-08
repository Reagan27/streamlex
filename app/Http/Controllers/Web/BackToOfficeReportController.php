<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vanguard\BackToOfficeReport;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Vanguard\County;

class BackToOfficeReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = BackToOfficeReport::with(['creator', 'creator.role', 'county'])
            ->withCount('attachments');

        $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();
        $this->applyReportVisibilityScope($query, $currentUser, $activeProjectId);

        if ($request->filled('search')) {
            $query->where('project_name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('county')) {
            $query->where('county_id', $request->county);
        }

        $reports = $query->latest()->paginate(20);
        $counties = County::orderBy('name')->get();

        return view('back-to-office-reports.index', compact('reports', 'counties'));
    }

    public function dashboard(Request $request)
    {
        $currentUser = auth()->user();
        $query = BackToOfficeReport::query()->with(['creator', 'county']);

        $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();
        $this->applyReportVisibilityScope($query, $currentUser, $activeProjectId);

        $reports = $query->latest()->get();
        $counties = County::orderBy('name')->get();

        $stats = [
            'total' => $reports->count(),
            'draft' => $reports->where('status', 'draft')->count(),
            'submitted' => $reports->where('status', 'submitted')->count(),
            'approved' => $reports->where('status', 'approved')->count(),
            'pending_approvals' => $reports->where('status', 'submitted')->count(),
            'overdue' => $reports->where('status', 'submitted')->where('created_at', '<', now()->subDays(7))->count(),
        ];

        $statusBreakdown = $reports->groupBy('status')->map(fn($items) => $items->count());
        $categoryBreakdown = $reports->groupBy('project_name')->map(fn($items) => $items->count());
        $countyBreakdown = $reports->groupBy('county_id')->map(fn($items) => $items->count());
        $monthlyTrend = $reports->groupBy(fn($report) => $report->created_at->format('Y-m'))->map(fn($items) => $items->count());
        $countyActivity = $reports->groupBy(fn($report) => $report->county->name ?? 'Unassigned')
            ->map(fn($items) => $items->count())
            ->sortDesc()
            ->take(6);
        $submitterBreakdown = $reports->groupBy(fn($report) => $report->creator->name ?? 'Unknown')
            ->map(fn($items) => $items->count())
            ->sortDesc();
        $topSubmitters = $submitterBreakdown->take(6);
        $lowSubmitters = $submitterBreakdown->sort(fn($a, $b) => $a <=> $b)->take(6);

        return view('back-to-office-reports.dashboard', compact(
            'stats',
            'reports',
            'counties',
            'statusBreakdown',
            'categoryBreakdown',
            'countyBreakdown',
            'countyActivity',
            'topSubmitters',
            'lowSubmitters',
            'monthlyTrend'
        ));
    }

    protected function applyReportVisibilityScope($query, $currentUser, ?int $activeProjectId = null)
    {
        if (!$currentUser->isAdmin()) {
            if ($currentUser->hasRole('Regional_Coordinator')) {
                $userCounties = $currentUser->counties->pluck('id')->toArray();
                $query->whereIn('county_id', $userCounties);
            } elseif ($currentUser->hasRole('County_Coordinator')) {
                $query->where('county_id', $currentUser->county_id);
            } else {
                $query->where('created_by', $currentUser->id);
            }
        }

        if ($activeProjectId) {
            $query->whereHas('creator.projects', function ($q) use ($activeProjectId) {
                $q->where('projects.id', $activeProjectId);
            });
        }

        return $query;
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $counties = County::orderBy('name')->get();
        } elseif ($user->hasRole('Regional_Coordinator')) {
            $counties = $user->counties;
        } else {
            $counties = County::where('id', $user->county_id)->get();
        }

        return view('back-to-office-reports.create', compact('counties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name'         => 'nullable|string|max:255',
            'county_id'            => 'required|exists:counties,id',
            'activity_date'        => 'nullable|date',
            'reported_by'          => 'nullable|string|max:255',
            'activity'             => 'nullable|string',
            'venue'                => 'nullable|string',
            'introduction'         => 'nullable|string',
            'objective'            => 'nullable|string',
            'output'               => 'nullable|string',
            'key_highlights'       => 'nullable|string',
            'challenges_and_risks' => 'nullable|string',
            'best_practices'       => 'nullable|string',
            'lessons_learnt'       => 'nullable|string',
            'recommendations'      => 'nullable|string',
            'way_forward'          => 'nullable|string',
            'prepared_by'          => 'nullable|string|max:255',
            'prepared_date'        => 'nullable|date',
            'attachments.*'        => 'nullable|file|max:2048',
            'status'               => 'required|in:draft,submitted',
            'participants'         => 'nullable|array',
            'budget'               => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $participants = $request->participants ?? [];
            foreach ($participants as $key => &$row) {
                $row = array_values($row);
                $row = [
                    0       => isset($row[0]) ? (int)$row[0] : 0,
                    1       => isset($row[1]) ? (int)$row[1] : 0,
                    2       => isset($row[2]) ? (int)$row[2] : 0,
                    'total' => ((int)($row[0] ?? 0)) + ((int)($row[1] ?? 0)) + ((int)($row[2] ?? 0)),
                ];
            }
            unset($row);

            $budget        = $request->budget ?? [];
            $budget_items  = $budget['item'] ?? [];
            $planned       = $budget['planned'] ?? [];
            $actual        = $budget['actual'] ?? [];
            $comment       = $budget['comment'] ?? [];
            $variance      = [];
            foreach ($planned as $i => $plan) {
                $variance[$i] = (float)($plan ?? 0) - (float)($actual[$i] ?? 0);
            }
            $budget = [
                'item'     => $budget_items,
                'planned'  => $planned,
                'actual'   => $actual,
                'variance' => $variance,
                'comment'  => $comment,
            ];

            $report = BackToOfficeReport::create([
                'project_name'         => $request->project_name,
                'county_id'            => $request->county_id,
                'activity_date'        => $request->activity_date,
                'reported_by'          => $request->reported_by,
                'activity'             => $request->activity,
                'venue'                => $request->venue,
                'introduction'         => $request->introduction,
                'objective'            => $request->objective,
                'output'               => $request->output,
                'key_highlights'       => $request->key_highlights,
                'challenges_and_risks' => $request->challenges_and_risks,
                'best_practices'       => $request->best_practices,
                'lessons_learnt'       => $request->lessons_learnt,
                'recommendations'      => $request->recommendations,
                'way_forward'          => $request->way_forward,
                'prepared_by'          => $request->prepared_by,
                'prepared_date'        => $request->prepared_date,
                'status'               => $request->status,
                'created_by'           => auth()->id(),
                'participants'         => $participants,
                'budget'               => $budget,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('back-to-office-reports/attachments', 'public');
                    $report->attachments()->create([
                        'file_path'     => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_type'     => $file->getClientMimeType(),
                        'file_size'     => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('back-to-office-reports.show', $report)
                ->with('success', 'Back to office report created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create back to office report: ' . $e->getMessage())->withInput();
        }
    }

    public function show(BackToOfficeReport $report)
    {
        $user = auth()->user();

        // Authorization: check the user can see this report
        if (!$user->isAdmin()) {
            if ($user->hasRole('Regional_Coordinator')) {
                $userCounties = $user->counties->pluck('id')->toArray();
                if (!in_array($report->county_id, $userCounties)) {
                    abort(403, 'You do not have access to this report.');
                }
            } elseif ($user->hasRole('County_Coordinator')) {
                if ($report->county_id !== $user->county_id) {
                    abort(403, 'You do not have access to this report.');
                }
            } else {
                if ($report->created_by !== $user->id) {
                    abort(403, 'You do not have access to this report.');
                }
            }
        }

        $report->load(['creator', 'approver', 'county', 'attachments']);
        return view('back-to-office-reports.show', compact('report'));
    }

    public function edit(BackToOfficeReport $report)
    {
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be edited.');
        }

        $user = auth()->user();

        if ($user->isAdmin()) {
            $counties = County::orderBy('name')->get();
        } elseif ($user->hasRole('Regional_Coordinator')) {
            $counties = $user->counties;
        } else {
            $counties = County::where('id', $user->county_id)->get();
        }

        // County coordinators can edit any report in their county
        if (!$user->isAdmin() && !$user->hasRole('Regional_Coordinator')) {
            if ($user->hasRole('County_Coordinator')) {
                if ($report->county_id !== $user->county_id) {
                    return back()->with('error', 'You can only edit reports for your assigned county.');
                }
            } else {
                // Field officers can only edit their own
                if ($report->created_by !== $user->id) {
                    return back()->with('error', 'You can only edit your own reports.');
                }
            }
        }

        return view('back-to-office-reports.edit', compact('report', 'counties'));
    }

    public function update(Request $request, BackToOfficeReport $report)
    {
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be updated.');
        }

        $user = auth()->user();

        $validatedData = $request->validate([
            'project_name'         => 'nullable|string|max:255',
            'county_id'            => 'required|exists:counties,id',
            'activity_date'        => 'nullable|date',
            'reported_by'          => 'nullable|string|max:255',
            'activity'             => 'nullable|string',
            'venue'                => 'nullable|string',
            'introduction'         => 'nullable|string',
            'objective'            => 'nullable|string',
            'output'               => 'nullable|string',
            'key_highlights'       => 'nullable|string',
            'challenges_and_risks' => 'nullable|string',
            'best_practices'       => 'nullable|string',
            'lessons_learnt'       => 'nullable|string',
            'recommendations'      => 'nullable|string',
            'way_forward'          => 'nullable|string',
            'prepared_by'          => 'nullable|string|max:255',
            'prepared_date'        => 'nullable|date',
            'attachments.*'        => 'nullable|file|max:2048',
            'status'               => 'required|in:draft,submitted',
            'participants'         => 'nullable|array',
            'budget'               => 'nullable|array',
        ]);

        if ($user->isAdmin() || $user->hasRole('Regional_Coordinator')) {
            $validatedData['county_id'] = $request->county_id;
        } else {
            $validatedData['county_id'] = $user->county_id;
        }

        try {
            DB::beginTransaction();

            $participants = $request->participants ?? $report->participants;
            foreach ($participants as $key => &$row) {
                $row = array_values($row);
                $row = [
                    0       => isset($row[0]) ? (int)$row[0] : 0,
                    1       => isset($row[1]) ? (int)$row[1] : 0,
                    2       => isset($row[2]) ? (int)$row[2] : 0,
                    'total' => ((int)($row[0] ?? 0)) + ((int)($row[1] ?? 0)) + ((int)($row[2] ?? 0)),
                ];
            }
            unset($row);

            $budget       = $request->budget ?? $report->budget;
            $budget_items = $budget['item'] ?? [];
            $planned      = $budget['planned'] ?? [];
            $actual       = $budget['actual'] ?? [];
            $comment      = $budget['comment'] ?? [];
            $variance     = [];
            foreach ($planned as $i => $plan) {
                $variance[$i] = (float)($plan ?? 0) - (float)($actual[$i] ?? 0);
            }
            $budget = [
                'item'     => $budget_items,
                'planned'  => $planned,
                'actual'   => $actual,
                'variance' => $variance,
                'comment'  => $comment,
            ];

            $validatedData['participants'] = $participants;
            $validatedData['budget']       = $budget;
            $report->update($validatedData);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('back-to-office-reports/attachments', 'public');
                    $report->attachments()->create([
                        'file_path'     => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_type'     => $file->getMimeType(),
                        'file_size'     => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('back-to-office-reports.show', $report)
                ->with('success', 'Back to office report updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update back to office report: ' . $e->getMessage());
        }
    }

    public function destroy(BackToOfficeReport $report)
    {
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be deleted.');
        }

        try {
            DB::beginTransaction();

            foreach ($report->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $report->attachments()->delete();
            $report->delete();

            DB::commit();

            return redirect()
                ->route('back-to-office-reports.index')
                ->with('success', 'Back to office report deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete back to office report.');
        }
    }

    public function approve(Request $request, BackToOfficeReport $report)
    {
        $request->validate([
            'decision' => 'required|in:approved,rejected',
            'approval_comments' => 'required|string|max:1000',
        ]);

        if (!$report->can_approve) {
            return back()->with('error', 'You do not have permission to approve this report.');
        }

        if ($report->status !== 'submitted') {
            return back()->with('error', 'Only submitted reports can be approved or rejected.');
        }

        $decision = $request->input('decision') === 'rejected' ? 'rejected' : 'approved';

        try {
            DB::beginTransaction();

            $report->update([
                'status'            => $decision,
                'approved_by'       => auth()->id(),
                'approved_at'       => now(),
                'approval_comments' => $request->approval_comments,
            ]);

            DB::commit();

            return back()->with('success', $decision === 'rejected'
                ? 'Report rejected successfully.'
                : 'Report approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update report status.');
        }
    }

    public function removeAttachment($reportId, $attachmentId)
    {
        $report = BackToOfficeReport::findOrFail($reportId);

        if ($report->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove attachments from approved reports.',
            ], 403);
        }

        $attachment = $report->attachments()->findOrFail($attachmentId);

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attachment removed successfully.',
        ]);
    }

    public function exportPdf(BackToOfficeReport $report)
    {
        set_time_limit(120);
        $report->load(['creator', 'approver', 'county', 'attachments']);
        $view = view('back-to-office-reports.pdf', compact('report'))->render();
        try {
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 15, 15, 15]);
            $html2pdf->setDefaultFont('helvetica');
            $html2pdf->writeHTML($view);
            return response($html2pdf->output('', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="BackToOfficeReport_' . ($report->project_name ?? $report->id) . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}