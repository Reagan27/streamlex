<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Users extends Plugin
{
    public function sidebar(): Item
    {
        $users = Item::create(__('Users'))
        ->route('users.index')
        ->active('users*')
        ->permissions('users.manage');

        $supervisor = Item::create(__('Supervisor Assignment'))
        ->route('assign-subordinates.index')
        ->active('assign-subordinates*')
        ->permissions('subordinates.assignment');

        return Item::create(__('Manage Users'))
            ->href('#users')
            ->icon('fas fa-users')
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['subordinates.assignment', 'users.manage'],
                    allRequired: false
                );
            })
            ->addChildren([
                $users,
                $supervisor 
            ]);
    }
}
