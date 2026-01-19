<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Support extends Plugin
{
    public function sidebar(): Item
    {
        $manageIssue = Item::create(__('Manage Issue'))
            ->route('support.manage')
            ->active('support*')
            ->permissions('support.manage');

        $raiseIssue = Item::create(__('Raise Issue'))
            ->route('support.index')
            ->active('supoort*')
            ->permissions('support.view');

        return Item::create(__('Support'))
            ->href('#support-dropdown')
            ->icon('fas fa-life-ring')
            ->permissions(['support.manage', 'support.view'])
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['support.manage', 'support.view'],
                    allRequired: false
                );
            })
            ->addChildren([
                $manageIssue,
                $raiseIssue
            ]);
    }
}