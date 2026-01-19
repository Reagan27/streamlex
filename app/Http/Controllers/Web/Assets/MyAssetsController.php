<?php

namespace Vanguard\Http\Controllers\Web\Assets;

use Vanguard\Http\Controllers\Controller;
use Vanguard\UserInventory;
use Vanguard\AssetAssignment;
use Illuminate\View\View;

class MyAssetsController extends Controller 
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $userId = auth()->id();

        // Get single assigned assets with their details
        $assignedAssets = AssetAssignment::where('assigned_to', $userId)
            ->where('assignment_status', AssetAssignment::STATUS_ASSIGNED) // Use assignment_status instead
            ->with(['asset' => function($query) {
                $query->select('id', 'name', 'sku_code', 'category');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get distributed assets (inventory) with their details
        $userInventory = UserInventory::where('user_id', $userId)
            ->where('quantity', '>', 0)
            ->with(['asset' => function($query) {
                $query->select('id', 'name', 'sku_code', 'category');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('asset.my-assets', compact('assignedAssets', 'userInventory'));
    }
}