<?php

namespace Vanguard\Http\Controllers\Web\Support;

use Carbon\Carbon;
use Vanguard\Http\Controllers\Controller;
use Vanguard\SupportIssue;
use Vanguard\Comment;
use Vanguard\IssuesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SupportIssueController extends Controller
{
    /**
     * Display the user's support issues.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {   $currentUser = auth()->user();
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $countyId = $request->input('county');
        $status = $request->input('status');

        $query = SupportIssue::query();

        $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();
    
        if ($user->hasRole('Admin')) {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->where('user_id', $user->id)->orderBy('created_at', 'desc');
        }
    
        if ($search) {
            $query->where('subject', 'like', "%{$search}%");
        }
    
        if ($countyId) {
            $query->whereHas('user.county', function ($q) use ($countyId) {
                $q->where('id', $countyId);
            });
        }

                // In index() method, add after base query:
        if ($activeProjectId = session('active_project_id')) {
            $query->whereHas('user.projects', function ($q) use ($activeProjectId) {
                $q->where('projects.id', $activeProjectId);
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
    
        $issues = $query->paginate($perPage);
        $counties = \Vanguard\County::pluck('name', 'id');
    
        return view('support.index', compact('issues', 'counties'));
    }     

    /**
     * Show the form for creating a new support issue.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        $categories = IssuesCategory::all();
        return view('support.create', compact('categories'));
    }

    /**
     * Store a newly created support issue.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:issues_categories,id',
            'priority' => 'required|string',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);
    
        $data = array_merge($validated, ['user_id' => Auth::id(), 'status' => 'Pending']);
    
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('support_attachments', 'public');
        }
    
        SupportIssue::create($data);
    
        return redirect()->route('support.index')->with('success', 'Issue raised successfully.');
    }

    /**
     * Store a new category for support issues.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:issues_categories,name',
        ]);

        IssuesCategory::create($validated);

        return redirect()->back()->with('success', 'Category added successfully.');
    }

    /**
     * Close a specific support issue.
     *
     * @param SupportIssue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close(SupportIssue $issue)
    {
        $issue->update(['status' => 'Closed']);

        return redirect()->route('support.index')->with('success', 'Issue closed successfully.');
    }

    public function escalate(Request $request, SupportIssue $issue)
    {
        $user = Auth::user();

        if ($user->hasRole('Regional_Coordinator') || $user->hasRole('County_Coordinator')) {
            $issue->update([
                'status' => 'Escalated',
                'escalated_by' => $user->id,
                'escalated_at' => now(),
            ]);

            Comment::create([
                'support_issue_id' => $issue->id,
                'user_id' => $user->id,
                'comment' => __('This issue has been escalated to the Admin for further review.'),
            ]);

            return redirect()->route('support.manage_show', $issue->id)->with('success', 'Issue escalated successfully.');
        }

        return redirect()->route('support.index')->with('error', 'Unauthorized action.');
    }

    /**
     * Display the admin view with all issues.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function manage(Request $request)
{
    $user = Auth::user();
    $perPage = $request->input('per_page', 10);
    $search = $request->input('search');
    $status = $request->input('status');
    $query = SupportIssue::query();
    
    if ($user->hasRole('Admin')) {
        $query->orderBy('created_at', 'desc');
        $counties = \Vanguard\County::pluck('name', 'id');
    } elseif ($user->hasRole('Regional_Coordinator')) {
        $assignedCountyIds = $user->counties()->pluck('counties.id');
        $query->whereHas('user.county', function ($q) use ($assignedCountyIds) {
            $q->whereIn('id', $assignedCountyIds);
        });
        $query->where('user_id', '!=', $user->id);
        $query->orderBy('created_at', 'desc');
        $counties = $user->counties()->pluck('counties.name', 'counties.id');
    } elseif ($user->hasRole('County_Coordinator')) {
        $query->whereHas('user.county', function ($q) use ($user) {
            $q->where('id', $user->county_id);
        });
        $query->where('user_id', '!=', $user->id);
        $query->whereHas('user.role', function ($q) {
            $q->where('name', '!=', 'Regional_Coordinator');
        });
        $query->orderBy('created_at', 'desc');
        $counties = [$user->county->id => $user->county->name];
    }

    $counties = $counties ?? collect();

    if ($search) {
        $query->where('subject', 'like', "%{$search}%");
    }

    if ($status) {
        $query->where('status', $status);
    }

    $totalIssues = $query->count();
    $resolvedIssues = (clone $query)->where('status', 'Closed')->count();
    $pendingIssues = (clone $query)->where('status', 'Pending')->count();
    $openIssues = (clone $query)->where('status', 'Open')->count();

    $issues = $query->paginate($perPage);

    $escalatedIssues = SupportIssue::where('status', 'Escalated')->paginate($perPage);

    $categories = IssuesCategory::all();
    $issuesData = $this->getIssuesData($categories->pluck('name', 'id')->toArray());

    return view('support.manage', compact(
        'issues',
        'totalIssues',
        'resolvedIssues',
        'pendingIssues',
        'openIssues',
        'issuesData',
        'counties',
        'categories',
        'user',
        'escalatedIssues'
    ));
}

    
    /**
     * Show a specific issue in the admin's view.
     *
     * @param SupportIssue $issue
     * @return \Illuminate\Contracts\View\View
     */
    public function manageShow(SupportIssue $issue)
    {
        $issue->load('comments.user');

        return view('support.manage_show', compact('issue'));
    }

    // public function downloadAttachment($filename)
    // {
    //     $path = 'public/support_attachments/' . $filename;

    //     if (Storage::exists($path)) {
    //         return Storage::download($path);
    //     }

    //     return redirect()->back()->with('error', __('File not found.'));
    // }

    /**
     * Show a specific issue in the user's view.
     *
     * @param SupportIssue $issue
     * @return \Illuminate\Contracts\View\View
     */
    public function userShow(SupportIssue $issue)
    {
        $issue->load('comments.user');

        return view('support.user_show', compact('issue'));
    }

    /**
     * Update the status of the issue for Admins.
     *
     * @param Request $request
     * @param SupportIssue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, SupportIssue $issue)
    {
        $user = Auth::user();
        
        if ($user->hasRole('Admin') || $user->hasRole('Regional_Coordinator') || $user->hasRole('County_Coordinator')) {
            $validated = $request->validate([
                'status' => 'required|string|in:Open,Closed',
            ]);

            $issue->update([
                'status' => $validated['status'],
            ]);

            return redirect()->route('support.manage_show', $issue->id)->with('success', 'Issue status updated successfully.');
        }

        return redirect()->route('support.index')->with('error', 'Unauthorized action.');
    }

    /**
     * Store a comment on a specific issue.
     *
     * @param Request $request
     * @param SupportIssue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeComment(Request $request, SupportIssue $issue)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'support_issue_id' => $issue->id,
            'user_id' => Auth::id(),
            'comment' => $request->input('comment'),
        ]);
        
        if (Auth::id() != $issue->user_id && $issue->status == 'Pending') {
            $issue->update(['status' => 'Open']);
        }
    
        return redirect()->route('support.manage_show', $issue->id)->with('success', 'Comment added successfully.');
    }

 public function downloadAttachment($filename)
{
    // Strip folder prefix if the full path was passed
    $filename = ltrim(str_replace('support_attachments/', '', $filename), '/');
    $path = 'support_attachments/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'Attachment not found.');
    }

    return Storage::disk('public')->download($path);
}

    private function getIssuesData(array $categories): array
    {
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $rawData = DB::table('support_issues')
            ->select(
                DB::raw('DATE(created_at) as date'),
                'category_id',
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date', 'category_id', 'status')
            ->orderBy('date')
            ->get();

        $categoryMap = IssuesCategory::pluck('name', 'id')->toArray();

        $issuesData = $rawData
            ->groupBy('date')
            ->map(function ($dateData) use ($categoryMap, $categories) {
                $categoryData = $dateData->groupBy('category_id')->map(function ($categoryData) use ($categoryMap) {
                    return $categoryData->pluck('count', 'status')->toArray();
                });

                $result = [];
                foreach ($categories as $category) {
                    $categoryId = array_search($category, $categoryMap);
                    $result[$category] = $categoryData[$categoryId] ?? [];
                }

                return $result;
            })
            ->toArray();

        return $issuesData;
    }
}
