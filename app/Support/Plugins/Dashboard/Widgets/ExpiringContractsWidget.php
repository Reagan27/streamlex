<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\Contract\ContractRepository;

class ExpiringContractsWidget extends Widget
{
    public ?string $width = '4';

    protected string|\Closure|array $permissions = 'contracts.manage';

    public function __construct(protected readonly ContractRepository $contracts)
    {
    }

    public function render(): View
    {
        $expiringContracts = $this->contracts->latestExpiringSoon(5);

        if ($expiringContracts->isEmpty()) {
            return view('plugins.dashboard.widgets.empty');
        }

        return view('plugins.dashboard.widgets.expiring-contracts', [
            'expiringContracts' => $expiringContracts,
        ]);
    }
}