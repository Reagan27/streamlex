<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Payments extends Plugin
{
    public function sidebar(): Item
    {
        $allPayments = Item::create(__('Payment Cycles'))
        ->route('payments.index')
        ->active('payments/index*')
        ->permissions('payments.import');

        $importPayments = Item::create(__('Import Payments'))
        ->route('payments.import')
        ->active('payments/import*')
        ->permissions('payments.import');

        $invoicePayments = Item::create(__('Invoice Payments'))
        ->route('invoice-payments.index')
        ->active('invoice-payments/index*')
        ->permissions('payments.import');

        $viewPayments = Item::create(__('View Payments'))
        ->route('payments.user')
        ->active('payments/view*')
        ->permissions('payments.view');


        return Item::create(__('Payments'))
            ->href('#payments-dropdown')
            ->icon('fas fa-money-bill-wave')
            ->permissions(['payments.import', 'payments.view'])
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['payments.import', 'payments.view'],
                    allRequired: false
                );
            })
            ->addChildren([
                $allPayments,
                $importPayments,
                $viewPayments,
                $invoicePayments
                
            ]);
    }
}