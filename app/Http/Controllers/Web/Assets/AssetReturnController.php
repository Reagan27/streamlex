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
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AssetReturnController extends Controller
{
    protected $roleHierarchyService;

    public function __construct(RoleHierarchyService $roleHierarchyService)
    {
        $this->roleHierarchyService = $roleHierarchyService;
        $this->middleware('auth');
    }

    /**
     * Show the return form for a specific user.
     *
     * @param User $user
     * @return View
     */
    public function showReturnForm(User $user): View
    {
        $distributedAssets = $user->distributedAssets()
            ->with('asset')
            ->get()
            ->groupBy('asset_id')
            ->map(function ($group) {
                $asset = $group->first()->asset;
                $totalDistributed = $group->sum('quantity');
                return [
                    'asset' => $asset,
                    'total_distributed' => $totalDistributed,
                ];
            });

        return view('asset.return.form', compact('user', 'distributedAssets'));
    }

    /**
     * Process the asset return.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function processReturn(Request $request, User $user): RedirectResponse
    {
        $this->validate($request, [
            'assets' => 'required|array',
            'assets.*' => 'exists:assets,id',
            'return_quantities' => 'required|array',
            'return_quantities.*' => 'required|integer|min:1',
        ]);

        $currentUser = Auth::user();

        if (!$this->roleHierarchyService->canProcessReturn($currentUser, $user)) {
            return back()->with('error', 'You do not have permission to process returns from this user.');
        }

        DB::beginTransaction();

        try {
            foreach ($request->assets as $assetId) {
                $returnQuantity = $request->return_quantities[$assetId];
                
                $totalDistributed = AssetDistribution::where('asset_id', $assetId)
                    ->where('distributed_to', $user->id)
                    ->sum('quantity');

                if ($totalDistributed < $returnQuantity) {
                    throw new \Exception("Invalid return quantity for asset ID {$assetId}");
                }

                $this->processAssetReturn($assetId, $returnQuantity, $user, $currentUser);
            }

            DB::commit();
            return redirect()->route('asset.distribution.form', $user)->with('success', 'Assets returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in asset return', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $currentUser->id,
                'recipient_id' => $user->id,
                'assets' => $request->assets,
                'return_quantities' => $request->return_quantities
            ]);
            return back()->with('error', 'Error processing asset return: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Process the return of a single asset.
     *
     * @param int $assetId
     * @param int $returnQuantity
     * @param User $user
     * @param User $currentUser
     * @throws \Exception
     */
    private function processAssetReturn(int $assetId, int $returnQuantity, User $user, User $currentUser): void
    {
        $distributions = AssetDistribution::where('asset_id', $assetId)
            ->where('distributed_to', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingReturn = $returnQuantity;

        foreach ($distributions as $distribution) {
            if ($remainingReturn <= 0) break;

            $quantityToReturn = min($remainingReturn, $distribution->quantity);
            $distribution->decrement('quantity', $quantityToReturn);
            $remainingReturn -= $quantityToReturn;

            if ($distribution->quantity == 0) {
                $distribution->delete();
            }
        }

        UserInventory::where('user_id', $user->id)
            ->where('asset_id', $assetId)
            ->decrement('quantity', $returnQuantity);

        if ($currentUser->hasRole(['Admin', 'Manager'])) {
            Asset::where('id', $assetId)->increment('quantity', $returnQuantity);
        } else {
            UserInventory::updateOrCreate(
                ['user_id' => $currentUser->id, 'asset_id' => $assetId],
                ['quantity' => DB::raw("quantity + $returnQuantity")]
            );
        }
    }

    /**
     * Retrieves the assets assigned to the currently logged-in user, excluding fully returned ones.
     *
     * @param Request $request
     * @return View
     */
}
