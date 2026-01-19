<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Training extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Trainings'))
            ->route('training.index')
            ->icon('fas fa-chalkboard-teacher')
            ->active('training*')
            ->permissions('training.view');  
    }
}