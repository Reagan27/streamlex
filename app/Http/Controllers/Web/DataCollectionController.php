<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\DataCollection;
use Illuminate\Http\Request;

class DataCollectionController extends Controller
{
    /**
     * Show a single data collection form with embedded Google Form.
     */
    public function view($slug)
    {
        $user = auth()->user();
        $projectIds = $user && method_exists($user, 'projects')
            ? $user->projects()->pluck('projects.id')->toArray()
            : [];

        $dataCollection = DataCollection::where('status', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where(function($q) use ($projectIds) {
                if (!empty($projectIds)) {
                    $q->whereIn('project_id', $projectIds);
                }
            })
            ->where('slug', $slug)
            ->firstOrFail();

        return view('data-collection.view', compact('dataCollection'));
    }

    /**
     * User-facing index — admins get redirected to admin panel.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user && (method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            return redirect()->route('admin.data-collection.index');
        }
        $projectIds = $user && method_exists($user, 'projects')
            ? $user->projects()->pluck('projects.id')->toArray()
            : [];

        $dataCollections = DataCollection::where('status', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where(function($q) use ($projectIds) {
                if (!empty($projectIds)) {
                    $q->whereIn('project_id', $projectIds);
                }
            })
            ->orderByDesc('created_at')
            ->get();

        return view('data-collection.index', compact('dataCollections'));
    }

    /**
     * Admin-facing index — shows all entries.
     */
    public function adminIndex()
    {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            abort(403);
        }
        $dataCollections = DataCollection::orderByDesc('created_at')->get();
        return view('data-collection.admin-index', compact('dataCollections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            abort(403);
        }
        return view('data-collection.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            abort(403);
        }
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|boolean',
            'iframe_code' => 'required|string',
            'sheet_url'   => 'nullable|url|max:500',
            'project_id'  => 'required|integer|exists:projects,id',
        ]);
        DataCollection::create($validated);
        return redirect()->route('admin.data-collection.index')
            ->with('success', 'Data Collection created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            abort(403);
        }
        $dataCollection = is_numeric($slug)
            ? DataCollection::findOrFail($slug)
            : DataCollection::where('slug', $slug)->firstOrFail();

        return view('data-collection.form', compact('dataCollection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            abort(403);
        }
        $dataCollection = is_numeric($slug)
            ? DataCollection::findOrFail($slug)
            : DataCollection::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|boolean',
            'iframe_code' => 'required|string',
            'sheet_url'   => 'nullable|url|max:500',
            'project_id'  => 'required|integer|exists:projects,id',
        ]);
        $dataCollection->update($validated);
        return redirect()->route('admin.data-collection.index')
            ->with('success', 'Data Collection updated.');
    }

    /**
     * Toggle status via AJAX.
     */
    public function toggleStatus(Request $request, $slug)
    {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            return response()->json(['success' => false], 403);
        }
        $dataCollection = is_numeric($slug)
            ? DataCollection::findOrFail($slug)
            : DataCollection::where('slug', $slug)->firstOrFail();

        $dataCollection->update(['status' => (bool) $request->input('status')]);
        return response()->json(['success' => true, 'status' => $dataCollection->status]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->hasRole('Admin'))) {
            abort(403);
        }
        $dataCollection = is_numeric($slug)
            ? DataCollection::findOrFail($slug)
            : DataCollection::where('slug', $slug)->firstOrFail();

        $dataCollection->delete();
        return redirect()->route('admin.data-collection.index')
            ->with('success', 'Data Collection deleted.');
    }
}