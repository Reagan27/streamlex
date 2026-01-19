<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Assets extends Plugin 
{
    public function sidebar(): Item
    {
        $assetsIndex = Item::create(__('Asset Management'))
            ->route('asset.index')
            ->active('assets')
            ->permissions('assets.view');

        $bulkAssign = Item::create(__('Asset Distribution'))
            ->route('asset.distribution.index')
            ->active('assets/bulk-assign')
            ->permissions('assets.bulk_assign');

        $assignAsset = Item::create(__('Assign Asset'))
            ->route('asset.assignment.index')
            ->active('assets/assign')
            ->permissions('assets.assign');

        $assetsHistory = Item::create(__('Assets History'))
            ->route('asset.history.index')  
            ->active('asset-history*')     
            ->permissions('assets.view');

        $myAssets = Item::create(__('My Assets'))
            ->route('my-assets')
            ->active('assets/my')
            ->permissions('assets.my');

        return Item::create(__('Assets'))
            ->href('#assets-dropdown')
            ->icon('fas fa-boxes')
            ->addChildren([
                $assetsIndex,
                $bulkAssign,
                $assignAsset,
                $myAssets,
                $assetsHistory
            ]);
    }
}