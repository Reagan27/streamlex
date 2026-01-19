<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Visualization extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Visualizations'))
            ->route('visualizations.index')
            ->icon('fas fa-chart-bar')
            ->active('visualizations*')
            ->permissions('visualizations.view');
    }
}
