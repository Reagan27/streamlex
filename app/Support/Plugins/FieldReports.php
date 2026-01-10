<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class FieldReports extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Field Reports'))
            ->route('field-reports.index')
            ->icon('fas fa-chalkboard-teacher')
            ->active('field-reports*')
            ->permissions('field-reports.manage');  
    }
}