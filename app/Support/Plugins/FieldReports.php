<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class FieldReports extends Plugin
{
    public function sidebar(): Item
    {
        $generalReports = Item::create(__('General Reports'))
            ->route('general-reports.index')
            ->active('general-reports*')
            ->permissions('general-reports.manage');

        $backToOfficeReports = Item::create(__('Back to Office Reports'))
            ->route('back-to-office-reports.index')
            ->active('back-to-office-reports*')
            ->permissions('back-to-office-reports.manage');

        $dataCollection = Item::create(__('Data Collection'))
            ->route('data-collection.index')
            ->active('data-collection*');

        return Item::create(__('Field Reports'))
            ->href('#field-reports-dropdown')
            ->icon('fas fa-chalkboard-teacher')
            ->permissions(['general-reports.manage', 'back-to-office-reports.manage'])
            ->addChildren([
                $generalReports,
                $backToOfficeReports,
                $dataCollection
            ]);
    }
}