<?php

namespace Vanguard\Http\Controllers\Web\Assets;
use Vanguard\Asset;
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

    public function index()
    {
        try {
            $assets = Asset::all();
            return view('asset.index', compact('assets'));
        } catch (\Exception $e) {
            return response()->view('errors.custom', ['message' => 'An error occurred while loading assets.'], 500);
        }
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