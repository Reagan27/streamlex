<?php

namespace Vanguard\Http\Controllers\Web\Assets;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Asset;
use Vanguard\AssetDistribution;
use Vanguard\UserInventory;
use Vanguard\User;
use Vanguard\Services\RoleHierarchyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssetDistributionController extends Controller
{
    protected $roleHierarchy;

    public function __construct(RoleHierarchyService $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = $this->getDistributableUsers($currentUser);
    
        if($request->filled('search')){
            $search = $request->search;
            $query = $query->where(function($q) use ($search){
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }
    
  
        $users = $query->paginate(20);
    
        return view('asset.distribution.index', compact('users'));
    }
    

    public function showDistributionForm(User $user)
    {
        $currentUser = auth()->user();
        $assets = $this->getDistributableAssets($currentUser);
        $distributedAssets = $user->distributedAssets()->with('asset')->get();
        
        $userInventory = UserInventory::where('user_id', $user->id)
            ->with('asset')
            ->get()
            ->keyBy('asset_id');
        return view('asset.distribution.form', compact('user', 'assets', 'distributedAssets','userInventory'));
    }

    private function getDistributableUsers(User $currentUser)
    {
        $query = User::query();
    
        if ($currentUser->isAdmin() || $currentUser->hasRole('Manager')) {
            $query->whereHas('role', function($q){
                $q->where('name','Regional_Coordinator');
            });
        } elseif ($currentUser->role->name === 'Regional_Coordinator') {
            $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
            $query->whereIn('county_id', $assignedCountyIds)
                  ->whereHas('role', function ($q) {
                      $q->where('name', 'County_Coordinator');
                  });
        } elseif ($currentUser->role->name === 'County_Coordinator') {
            $query->where('county_id', $currentUser->county_id)
                  ->whereHas('role', function ($q) {
                      $q->where('name', 'Supervisor');
                  });
        } elseif ($currentUser->role->name === 'Supervisor') {
            $query->where('supervisor_id', $currentUser->id)
                  ->whereHas('role', function ($q) {
                      $q->where('name', 'Field_Officer');
                  });
        } else {
            // Other roles can't distribute assets
            return User::where('id', 0);
        }
    
        return $query;
    }

    private function getDistributableAssets(User $currentUser)
    {
        if ($currentUser->hasRole(['Admin', 'Manager'])) {
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

    public function processDistribution(Request $request, User $user)
    {
        $this->validate($request, [
            'assets' => 'required|array',
            'assets.*' => 'exists:assets,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        $currentUser = auth()->user();

        if (!$this->canDistribute($currentUser, $user)) {
            return back()->with('error', 'You do not have permission to distribute assets to this user.');
        }

        DB::beginTransaction();

        try {
            foreach ($request->assets as $assetId) {
                $quantity = $request->quantities[$assetId];
                
                if ($currentUser->hasRole(['Admin', 'Manager'])) {
                    $this->distributeFromMainInventory($assetId, $quantity, $currentUser, $user);
                } else {
                    $this->distributeFromUserInventory($assetId, $quantity, $currentUser, $user);
                }
            }

            DB::commit();
            return redirect()->route('asset.distribution.form', $user)->with('success', 'Assets distributed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in asset distribution', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error distributing assets: ' . $e->getMessage())->withInput();
        }
    }

    private function distributeFromMainInventory($assetId, $quantity, User $distributor, User $recipient)
    {
        $asset = Asset::lockForUpdate()->findOrFail($assetId);
        if ($asset->quantity < $quantity) {
            throw new \Exception("Insufficient quantity for asset: {$asset->name}");
        }
        $asset->decrement('quantity', $quantity);

        $this->createDistribution($assetId, $quantity, $distributor, $recipient);
        $this->updateRecipientInventory($assetId, $quantity, $recipient);
    }

    private function distributeFromUserInventory($assetId, $quantity, User $distributor, User $recipient)
    {
        $inventory = UserInventory::where('user_id', $distributor->id)
            ->where('asset_id', $assetId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($inventory->quantity < $quantity) {
            throw new \Exception("Insufficient quantity for asset: {$inventory->asset->name}");
        }
        $inventory->decrement('quantity', $quantity);

        $this->createDistribution($assetId, $quantity, $distributor, $recipient);
        $this->updateRecipientInventory($assetId, $quantity, $recipient);
    }

    private function createDistribution($assetId, $quantity, User $distributor, User $recipient)
    {
        AssetDistribution::create([
            'asset_id' => $assetId,
            'distributed_to' => $recipient->id,
            'distributed_by' => $distributor->id,
            'quantity' => $quantity,
            'comments' => request('comments'),
        ]);
    }

    private function updateRecipientInventory($assetId, $quantity, User $recipient)
    {
        UserInventory::updateOrCreate(
            ['user_id' => $recipient->id, 'asset_id' => $assetId],
            ['quantity' => DB::raw("quantity + $quantity")]
        );
    }

    private function processAssetReturn($assetId, $returnQuantity, User $user, User $currentUser)
    {
        // First check the user's current holdings
        $userInventory = UserInventory::where('user_id', $user->id)
            ->where('asset_id', $assetId)
            ->first();

        if (!$userInventory || $userInventory->quantity < $returnQuantity) {
            $asset = Asset::find($assetId);
            throw new \Exception("Invalid return quantity for asset: {$asset->name}. User only has {$userInventory->quantity} items available to return.");
        }

        // Decrement user's inventory first
        $userInventory->decrement('quantity', $returnQuantity);

        // Update the distribution records
        $remainingReturn = $returnQuantity;
        $distributions = AssetDistribution::where('asset_id', $assetId)
            ->where('distributed_to', $user->id)
            ->where('quantity', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($distributions as $distribution) {
            if ($remainingReturn <= 0) break;

            $quantityToReturn = min($remainingReturn, $distribution->quantity);
            $distribution->decrement('quantity', $quantityToReturn);
            $remainingReturn -= $quantityToReturn;

            if ($distribution->quantity == 0) {
                $distribution->delete();
            }
        }

        // Return to appropriate inventory (main inventory for admin/manager, user inventory for others)
        if ($currentUser->hasRole(['Admin', 'Manager'])) {
            Asset::where('id', $assetId)->increment('quantity', $returnQuantity);
        } else {
            UserInventory::updateOrCreate(
                ['user_id' => $currentUser->id, 'asset_id' => $assetId],
                ['quantity' => DB::raw("quantity + $returnQuantity")]
            );
        }
    }

    public function processReturn(Request $request, User $user)
    {
        $this->validate($request, [
            'assets' => 'required|array',
            'assets.*' => 'exists:assets,id',
            'return_quantities' => 'required|array',
            'return_quantities.*' => 'required|integer|min:1',
        ]);

        $currentUser = auth()->user();

        if (!$this->canProcessReturn($currentUser, $user)) {
            return back()->with('error', 'You do not have permission to process returns from this user.');
        }

        DB::beginTransaction();

        try {
            foreach ($request->assets as $assetId) {
                $returnQuantity = $request->return_quantities[$assetId];
                $this->processAssetReturn($assetId, $returnQuantity, $user, $currentUser);
            }

            DB::commit();
            return redirect()->route('asset.distribution.form', $user)
                ->with('success', 'Assets returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in asset return', [
                'error' => $e->getMessage(),
                'user_id' => $currentUser->id,
                'recipient_id' => $user->id,
                'assets' => $request->assets,
                'return_quantities' => $request->return_quantities
            ]);
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function canDistribute(User $distributor, User $recipient)
    {
        return $distributor->canSupervise($recipient);
    }

    private function canProcessReturn(User $processor, User $returnee)
    {
        return $processor->canSupervise($returnee) || $returnee->supervisor_id === $processor->id;
    }
}