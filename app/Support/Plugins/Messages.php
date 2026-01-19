<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Messages extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Messages'))
            ->route('messages.index')
            ->icon('fas fa-envelope')
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['messages.send_bulk', 'messages.send_single', 'messages.send_select', 'messages.templates'],
                    allRequired: false
                );
            });
    }
}