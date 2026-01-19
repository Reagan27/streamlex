<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Contracting extends Plugin
{
    public function sidebar(): Item
    {
        $contracts = Item::create(__('Contracts Management'))
            ->route('contracts.index')
            ->active('contracts*')
            ->permissions('contracts.manage');

        $approval = Item::create(__('Contracts Approval'))
            ->route('approval.index')
            ->active('approval*')
            ->permissions('contracts.approval');

        $allContracts = Item::create(__('Contracts List'))
            ->route('contractsList.index')
            ->active('contractsList*')
            ->permissions('contracts.approval');

        $myContract = Item::create(__('My Contract'))
            ->route('contract.index')
            ->active('contracts*')
            ->permissions('contract.status');

        return Item::create(__('Contracting'))
            ->href('#contracts-dropdown')
            ->icon('fas fa-file-alt')
            ->permissions(['contracts.manage', 'contracts.approval','contract.status'])
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['contracts.manage', 'contracts.approval','contract.status'],
                    allRequired: false
                );
            })
            ->addChildren([
                $contracts,
                $approval,       
                $allContracts,
                $myContract
            ]);
    }
}