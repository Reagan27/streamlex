<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Communication extends Plugin
{
    public function sidebar(): Item
    {
        $messages = Item::create(__('Messages'))
            ->route('messages.index')
            ->icon('fas fa-envelope')
            ->permissions(function (User $user) {
                return $user->hasPermission('messages.manage');
            });
        $bot = Item::create(__('ChatBot'))
            ->route('bot.index')
            ->icon('fas fa-envelope')
            ->permissions(function (User $user) {
                return $user->hasPermission('messages.manage');
            });

        $groups = Item::create(__('Groups'))
            ->route('groups.index')
            ->icon('fas fa-users')
            ->permissions(function (User $user) {
                return $user->hasPermission('groups.manage');
            });

        $emails = Item::create(__('Emails'))
            ->route('emails.index')
            ->icon('fas fa-paper-plane')
            ->permissions(function (User $user) {
                return $user->hasPermission('emails.send');
            });
        return Item::create(__('Communication'))
            ->href('#communication-dropdown')
            ->icon('fas fa-comments')
            ->permissions(function (User $user) {
                return $user->hasPermission('messages.manage') ||
                       $user->hasPermission('groups.manage') ||
                       $user->hasPermission('emails.send');
                     
            })
            ->addChildren([
                $messages,
                $groups,
                $emails,
                $bot,  
            ]);
    }
}