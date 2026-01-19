<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Vanguard\UserContractSignature;

class ActiveContractStatusWidget extends Widget
{
    public ?string $width = '4';

    protected string|\Closure|array $permissions = 'users.create';

    protected array $contractData;

    public function render(): View
    {
        return view('plugins.dashboard.widgets.active-contract-status', [
            'contractData' => $this->getContractData(),
            'statuses' => $this->getStatuses(),
        ]);
    }

    private function getContractData(): array
    {
        if (isset($this->contractData)) {
            return $this->contractData;
        }

        // Get current date for expiry calculation
        $now = Carbon::now();

        // Query for active contracts (approved and accepted, not expired)
        $rawData = UserContractSignature::select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->whereIn('status', ['draft','approved', 'accepted'])
            // ->whereHas('contract', function($query) use ($now) {
            //     $query->where(function($q) use ($now) {
            //         $q->whereRaw('DATE_ADD(start_date, INTERVAL number_of_days DAY) > ?', [$now]);
            //     });
            // })
            ->groupBy('status')
            ->get();

        $statuses = $this->getStatuses();
        $this->contractData = array_fill_keys($statuses, 0);

        foreach ($rawData as $item) {
            $this->contractData[$item->status] = $item->count;
        }

        return $this->contractData;
    }

    private function getStatuses(): array
    {
        return ['draft','approved', 'accepted'];
    }
}