<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\Contract\ContractRepository;

class LatestContracts extends Widget
{
    public ?string $width = '4';

    protected string|\Closure|array $permissions = 'contracts.manage';

    public function __construct(protected readonly ContractRepository $contracts)
    {
    }

    public function render(): View
    {
        return view('plugins.dashboard.widgets.latest-contracts', [
            'latestContracts' => $this->contracts->latest(6),
        ]);
    }
}