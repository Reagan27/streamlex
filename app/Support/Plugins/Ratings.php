<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Ratings extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Ratings'))
            ->route('ratings.index')
            ->icon('fas fa-star-half-alt')
            ->active('rating*')
            ->permissions('ratings.manage');  
    }
}