<?php

namespace Vanguard\Providers;

use Vanguard\Plugins\VanguardServiceProvider as BaseVanguardServiceProvider;
use Vanguard\Support\Plugins\Dashboard\Widgets\ContractStatusWidget;
use Vanguard\Support\Plugins\Dashboard\Widgets\FieldOfficers;
use Vanguard\Support\Plugins\Dashboard\Widgets\TotalAppraisals;
use Vanguard\Support\Plugins\Dashboard\Widgets\TotalUsers;
use Vanguard\Support\Plugins\Dashboard\Widgets\RegistrationHistory;
use Vanguard\UserActivity\Widgets\ActivityWidget;
use Vanguard\Support\Plugins\Dashboard\Widgets\HelloWidget;
use Illuminate\Support\Facades\Log;
use Vanguard\Support\Plugins\Dashboard\Widgets\CountyCoordinators;
use Vanguard\Support\Plugins\Dashboard\Widgets\DurableAssetsWidget;
use Vanguard\Support\Plugins\Dashboard\Widgets\ExpiringContractsWidget;
use Vanguard\Support\Plugins\Dashboard\Widgets\LatestContracts;
use Vanguard\Support\Plugins\Dashboard\Widgets\MessageWidget;
use Vanguard\Support\Plugins\Dashboard\Widgets\RegionalCoordinators;
use Vanguard\Support\Plugins\Dashboard\Widgets\Supervisors;
// use Vanguard\Support\Plugins\Messages;
use Vanguard\Support\Plugins\Communication;
use Vanguard\Support\Plugins\Dashboard\Widgets\ActiveContractStatusWidget;
use Vanguard\Support\Plugins\Dashboard\Widgets\PaymentDashboardWidget;
use Vanguard\Support\Plugins\Dashboard\Widgets\SupportIssuesWidget;
use Vanguard\Support\Plugins\Support;
use Vanguard\Support\Plugins\Visualization;
// use Vanguard\Support\Plugins\Dashboard\Widgets\MessageWidget;

class VanguardServiceProvider extends BaseVanguardServiceProvider
{
    protected function plugins(): array
    {
        return [
            \Vanguard\Support\Plugins\Dashboard\Dashboard::class,
            \Vanguard\Support\Plugins\Visualization::class,
            \Vanguard\Support\Plugins\Users::class,
            \Vanguard\Support\Plugins\Assets::class,
            \Vanguard\Support\Plugins\Contracting::class,
            \Vanguard\Support\Plugins\Settings::class,
            \Vanguard\Announcements\Announcements::class,
            \Vanguard\Support\Plugins\Communication::class, 
            \Vanguard\Support\Plugins\Training::class,         
            \Vanguard\Support\Plugins\Appraisals::class,
            \Vanguard\Support\Plugins\Support::class,
            \Vanguard\Support\Plugins\Payments::class,
            \Vanguard\Support\Plugins\FieldReports::class,
            \Vanguard\Support\Plugins\Ratings::class,
            \Vanguard\Support\Plugins\Reporting::class,
            \Vanguard\UserActivity\UserActivity::class,
            

        ];
    }

    protected function widgets(): array
    {
        $widgets = [
            // TotalUsers::class,
            ContractStatusWidget::class,
            FieldOfficers::class,
            Supervisors::class,
            CountyCoordinators::class,
            RegionalCoordinators::class,
            TotalAppraisals::class,
            // RegistrationHistory::class,
            // LatestContracts::class,
            SupportIssuesWidget::class,
            DurableAssetsWidget::class,
            ActiveContractStatusWidget::class,
            PaymentDashboardWidget::class,
            MessageWidget::class,
            ExpiringContractsWidget::class,
            ActivityWidget::class,
            HelloWidget::class,
            // MessageWidget::class,
        ];
        return $widgets;
    }
}
