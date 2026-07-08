<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vanguard\GeneralReport;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Vanguard\County;

class GeneralReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = GeneralReport::with(['creator', 'creator.role', 'county'])
            ->withCount('attachments');

        $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();
        $this->applyReportVisibilityScope($query, $currentUser, $activeProjectId);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('county')) {
            $query->where('county_id', $request->county);
        }

        $reports = $query->latest()->paginate(20);
        $counties = County::orderBy('name')->get();

        return view('general-reports.index', compact('reports', 'counties'));
    }

    public function dashboard(Request $request)
    {
        $currentUser = auth()->user();
        $query = GeneralReport::query()->with(['creator', 'county']);

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
        $categoryBreakdown = $reports->groupBy('category')->map(fn($items) => $items->count());
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

        return view('general-reports.dashboard', compact(
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

        if ($user->isAdmin() || $user->hasRole('Manager')) {
            $counties = County::orderBy('name')->get();
        } elseif ($user->hasRole('Regional_Coordinator')) {
            $counties = $user->counties;
        } else {
            $counties = County::where('id', $user->county_id)->get();
        }

        return view('general-reports.create', compact('counties'));
    }

    public function store(Request $request)
    {
        \Log::info('GeneralReportController@store: incoming request', $request->all());

        try {
            $validated = $request->validate([
                'title'            => 'required|string|max:255',
                'category'         => 'required|string',
                'subcategory'      => 'required|string',
                'county_id'        => 'required|exists:counties,id',
                'content'          => 'nullable|string',
                'summary'          => 'nullable|string',
                'recommendations'  => 'nullable|string',
                'attachments.*'    => 'nullable|file|max:2048',
                'status'           => 'required|in:draft,submitted',
            ]);
            \Log::info('GeneralReportController@store: validated data', $validated);

            DB::beginTransaction();

            $report = GeneralReport::create([
                'title'           => $request->title,
                'category'        => $request->category,
                'subcategory'     => $request->subcategory,
                'county_id'       => $request->county_id,
                'content'         => $request->content,
                'summary'         => $request->summary,
                'recommendations' => $request->recommendations,
                'status'          => $request->status,
                'created_by'      => auth()->id(),
            ]);
            \Log::info('GeneralReportController@store: report created', ['id' => $report->id]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('general-reports/attachments', 'public');
                    $report->attachments()->create([
                        'file_path'     => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_type'     => $file->getClientMimeType(),
                        'file_size'     => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            \Log::info('GeneralReportController@store: committed');

            return redirect()
                ->route('general-reports.show', $report)
                ->with('success', 'General report created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('GeneralReportController@store: exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to create general report: ' . $e->getMessage())->withInput();
        }
    }

    public function show(GeneralReport $report)
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
        return view('general-reports.show', compact('report'));
    }

    public function edit(GeneralReport $report)
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

        return view('general-reports.edit', compact('report', 'counties'));
    }

    public function update(Request $request, GeneralReport $report)
    {
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be updated.');
        }

        $user = auth()->user();

        $validatedData = $request->validate([
            'title'           => 'required|string|max:255',
            'category'        => 'required|string',
            'subcategory'     => 'required|string',
            'content'         => 'nullable|string',
            'summary'         => 'nullable|string',
            'recommendations' => 'nullable|string',
            'attachments.*'   => 'nullable|file|max:2048',
            'status'          => 'required|in:draft,submitted',
        ]);

        if ($user->isAdmin() || $user->hasRole('Regional_Coordinator')) {
            $validatedData['county_id'] = $request->validate([
                'county_id' => 'required|exists:counties,id',
            ])['county_id'];
        } else {
            $validatedData['county_id'] = $user->county_id;
        }

        try {
            DB::beginTransaction();

            $report->update($validatedData);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('general-reports/attachments', 'public');
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
                ->route('general-reports.show', $report)
                ->with('success', 'General report updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update general report: ' . $e->getMessage());
        }
    }

    public function destroy(GeneralReport $report)
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
                ->route('general-reports.index')
                ->with('success', 'General report deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete general report.');
        }
    }

    public function approve(Request $request, GeneralReport $report)
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
        $report = GeneralReport::findOrFail($reportId);

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
}