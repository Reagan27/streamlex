<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;


class Appraisals extends Plugin
{
    public function sidebar(): Item
    {
        $appraisals = Item::create(__('Appraise'))
        ->route('appraisals.index')
        ->active('appraisal*')
        ->permissions('manage.appraisals');

        $recommendation = Item::create(__('Reccomendation Form'))
            ->route('recommendation_certificates.index')
            ->active('recommendation*')
            ->permissions('manage.recommendation');

        return Item::create(__('Appraisals'))
            ->href('#appraisals-dropdown')
            ->icon('fas fa-user')
            ->permissions(['manage.appraisals', 'manage.recommendation'])
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['manage.appraisals', 'manage.recommendation'],
                    allRequired: false
                );
            })
            ->addChildren([
                $recommendation,
                $appraisals
            ]);
    }
}