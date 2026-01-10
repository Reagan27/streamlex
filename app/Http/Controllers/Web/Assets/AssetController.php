<?php

namespace Vanguard\Http\Controllers\Web\Assets;
use Vanguard\Asset;
use Vanguard\Projects;
use Vanguard\AssetAssignment;
use Vanguard\AssetDistribution;
use Vanguard\UserInventory;
use Vanguard\Services\RoleHierarchyService;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class AssetController extends Controller
{
    protected $roleHierarchy;

    public function __construct(RoleHierarchyService $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

   public function index(Request $request)
{
 $query = Asset::with(['assignments.user', 'distributions.distributedTo']);

    // Optional: existing filters (search, category, status, etc.)
    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%")
              ->orWhere('sku_code', 'like', "%{$request->search}%")
              ->orWhere('serial_number', 'like', "%{$request->search}%")
              ->orWhere('imei_number', 'like', "%{$request->search}%");
    }

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

  // PROJECT FILTER
    if ($request->filled('project_id')) {
        $projectId = (int) $request->project_id;

        $currentUser = auth()->user();

        if (
            $currentUser->isAdmin() ||
            $currentUser->hasRole('Manager') ||
            $currentUser->hasRole('Finance') ||
            in_array($currentUser->role->name, ['Regional_Coordinator', 'County_Coordinator'])
        ) {
            $query->where(function ($q) use ($projectId) {
                $q->whereHas('assignments.user.projects', function ($subQ) use ($projectId) {
                    $subQ->where('projects.id', $projectId)
                         ->where('projects_user.is_active_project', true);
                })
                ->orWhereHas('distributions.distributedTo.projects', function ($subQ) use ($projectId) {
                    $subQ->where('projects.id', $projectId)
                         ->where('projects_user.is_active_project', true);
                });
            });
        }
    }

    $assets = $query->paginate(20);

    $categories = Asset::distinct('category')->pluck('category');

    return view('asset.index', compact('assets', 'categories'));
}


    public function create()
    {
        return view('asset.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sku_code' => 'required|unique:assets,sku_code,NULL,id,deleted_at,NULL',
                'name' => 'required',
                'quantity' => 'required|integer|min:0',
                'category' => 'required|in:Consumable,Durable',
            ]);
    
            $existingAsset = Asset::withTrashed()->where('sku_code', $request->sku_code)->first();
            if ($existingAsset) {
                if ($existingAsset->trashed()) {
                    $existingAsset->restore();
                    $existingAsset->update($validatedData);
                    return redirect()->route('asset.index')->with('success', 'Asset restored and updated successfully.');
                } else {
                    return back()->withInput()->withErrors(['sku_code' => 'An asset with this SKU code already exists.']);
                }
            }
    
            Asset::create($validatedData);
            return redirect()->route('asset.index')->with('success', 'Asset created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating asset: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create asset. ' . $e->getMessage()]);
        }
    }

    public function edit(Asset $asset)
    {
        return view('asset.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validatedData = $request->validate([
            'sku_code' => 'required|unique:assets,sku_code,' . $asset->id,
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'category' => 'required|in:Consumable,Durable',
        ]);

        $asset->update($validatedData);

        return redirect()->route('asset.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('asset.index')->with('success', 'Asset deleted successfully.');
    }

    public function getAssignableUsers(Request $request)
    {
        $assetId = $request->input('asset_id');
        $asset = Asset::findOrFail($assetId);
        $currentUser = auth()->user();

        $assignableUsers = $this->roleHierarchy->getAssignableUsers($currentUser, $asset);

        return response()->json($assignableUsers);
    }

    public function getDistributableUsers(Request $request)
    {
        $assetId = $request->input('asset_id');
        $asset = Asset::findOrFail($assetId);
        $currentUser = auth()->user();

        $distributableUsers = $this->roleHierarchy->getDistributableUsers($currentUser, $asset);

        return response()->json($distributableUsers);
    }
}