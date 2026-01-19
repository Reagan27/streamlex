<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Reporting extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Reports'))
            ->route('report-wizard.index')
            ->icon('fas fa-chart-line') 
            ->active('reports*')
            ->permissions('view.reports');
    }
}
