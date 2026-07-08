<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Compliance extends Plugin
{
    public function sidebar(): Item
    {
        $dashboard = Item::create(__('Compliance Management'))
            ->route('compliance.index')
            ->active('compliance')
            ->permissions('compliance.view');

        return Item::create(__('Compliance'))
            ->href('#compliance-dropdown')
            ->icon('fas fa-file-contract')
            ->addChildren([
                $dashboard,
            ]);
    }
}
