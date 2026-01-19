<?php

namespace Vanguard\Repositories\Contract;

use Vanguard\AdminContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ContractRepository
{
    public function latestExpiringSoon($count = 6): Collection
    {
        return AdminContract::where('status', 'published')
            ->get()
            ->map(function ($contract) {
                $startDate = Carbon::parse($contract->start_date);
                $expiryDate = $startDate->copy()->addDays($contract->number_of_days);
                $daysRemaining = now()->diffInDays($expiryDate, false);

                return [
                    'id' => $contract->id,
                    'title' => $contract->title,
                    'start_date' => $startDate->toDateString(),
                    'expiry_date' => $expiryDate->toDateString(),
                    'days_remaining' => $daysRemaining,
                    'is_expired' => $daysRemaining < 0,
                ];
            })
            ->filter(function ($contract) {
                return $contract['days_remaining'] < 7 && $contract['days_remaining'] >= 0;
            })
            ->sortBy('days_remaining')
            ->take($count);
    }
}