<?php

namespace Vanguard\Http\Controllers\Web\Assets;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Asset;
use Vanguard\AssetAssignment;
use Vanguard\User;
use Vanguard\UserInventory;
use Vanguard\Services\RoleHierarchyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssetAssignmentController extends Controller
{
    protected $roleHierarchy;

    public function __construct(RoleHierarchyService $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = User::query();

        if ($currentUser->isAdmin() || $currentUser->hasRole('Manager')) {
            $query->whereHas('role', function($q){
                $q->where('name', 'Regional_Coordinator');
            });
        } elseif ($currentUser->role->name === 'Regional_Coordinator') {
            $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
            $query->whereIn('county_id', $assignedCountyIds)
                  ->whereHas('role', function ($q) {
                      $q->where('name','County_Coordinator');
                  });
        } elseif ($currentUser->role->name === 'County_Coordinator') {
            $query->where('county_id', $currentUser->county_id)
                  ->whereHas('role', function ($q) {
                      $q->where('name','Supervisor');
                  });
        } elseif ($currentUser->role->name === 'Supervisor') {
            $query->where('supervisor_id', $currentUser->id)
                  ->whereHas('role', function ($q) {
                      $q->where('name', 'Field_Officer');
                  });
        } else {
            $query->where('id', null);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(20);
        return view('asset.assignment.index', compact('users'));
    }

    public function showAssignmentForm(User $user)
    {
        $currentUser = auth()->user();
        $assets = $this->getAssignableAssets($currentUser);
        $assignedAssets = $user->assetAssignments()
            ->where('assignment_status', AssetAssignment::STATUS_ASSIGNED)
            ->with('asset')
            ->get();

        return view('asset.assignment.form', compact('user', 'assets', 'assignedAssets'));
    }

    public function assign(Request $request, User $user)
    {
        $this->validate($request, [
            'asset_id' => 'required|exists:assets,id',
            'imei_number' => 'required_if:asset_category,Durable',
            'serial_number' => 'required_if:asset_category,Durable',
            'physical_condition' => 'required|in:' . implode(',', AssetAssignment::getPhysicalConditions()),
            'comments' => 'nullable',
        ]);

        $currentUser = auth()->user();
        $asset = Asset::findOrFail($request->asset_id);

        if (!$this->canAssign($currentUser, $user)) {
            return back()->with('error', 'You do not have permission to assign assets to this user.');
        }

        if ($this->hasActiveAssetAssignment($asset, $user)) {
            return back()->with('error', 'This asset has already been assigned to the user.');
        }

        DB::beginTransaction();

        try {
            if ($currentUser->isAdmin() || $currentUser->hasRole('Manager')) {
                if ($asset->quantity < 1) {
                    throw new \Exception("Insufficient quantity for asset: {$asset->name}");
                }
                $asset->decrement('quantity');
            } else {
                $userInventory = UserInventory::where('user_id', $currentUser->id)
                    ->where('asset_id', $asset->id)
                    ->first();

                if (!$userInventory || $userInventory->quantity < 1) {
                    throw new \Exception("Insufficient quantity for asset: {$asset->name}");
                }
                $userInventory->decrement('quantity');
            }

            $assignment = AssetAssignment::create([
                'asset_id' => $asset->id,
                'assigned_to' => $user->id,
                'assigned_by' => $currentUser->id,
                'imei_number' => $request->imei_number,
                'serial_number' => $request->serial_number,
                'physical_condition' => $request->physical_condition,
                'assignment_status' => AssetAssignment::STATUS_ASSIGNED,
                'comments' => $request->comments,
            ]);

            DB::commit();
            return redirect()->route('asset.assignment.form', $user)
                           ->with('success', 'Asset assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in asset assignment', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error assigning asset: ' . $e->getMessage())->withInput();
        }
    }

    public function returnAsset(Request $request, AssetAssignment $assignment)
    {
        $this->validate($request, [
            'physical_condition' => 'required|in:' . implode(',', AssetAssignment::getPhysicalConditions()),
            'comments' => 'nullable',
        ]);
    
        DB::beginTransaction();
    
        try {
            $asset = $assignment->asset;
            $assignedBy = $assignment->assignedBy;

            $assignment->update([
                'physical_condition' => $request->physical_condition,
                'assignment_status' => AssetAssignment::STATUS_RETURNED,
                'comments' => $request->comments,
            ]);
    
            if ($asset->category === 'Durable') {
                if ($assignedBy->isAdmin() || $assignedBy->hasRole('Manager')) {
                    $asset->increment('quantity');
                } else {
                    UserInventory::updateOrCreate(
                        ['user_id' => $assignedBy->id, 'asset_id' => $asset->id],
                        ['quantity' => DB::raw('quantity + 1')]
                    );
                }
            }
    
            DB::commit();
            return redirect()->route('asset.assignment.form', $assignment->assigned_to)
                ->with('success', 'Asset returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in asset return', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error returning asset: ' . $e->getMessage())->withInput();
        }
    }

    private function getAssignableAssets(User $currentUser)
    {
        if ($currentUser->isAdmin() || $currentUser->hasRole('Manager')) {
            return Asset::where('quantity', '>', 0)->get();
        } else {
            return UserInventory::where('user_id', $currentUser->id)
                ->where('quantity', '>', 0)
                ->with('asset')
                ->get()
                ->map(function ($inventory) {
                    $asset = $inventory->asset;
                    $asset->quantity = $inventory->quantity;
                    return $asset;
                });
        }
    }

    private function canAssign(User $assigner, User $assignee)
    {
        return $assigner->canSupervise($assignee);
    }

    private function hasActiveAssetAssignment(Asset $asset, User $user)
    {
        return AssetAssignment::where('asset_id', $asset->id)
            ->where('assigned_to', $user->id)
            ->where('assignment_status', AssetAssignment::STATUS_ASSIGNED)
            ->exists();
    }
}
