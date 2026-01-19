<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Repositories\Contract\ContractRepository;
use Illuminate\Support\Facades\Log;

class ContractStatusWidget extends Widget
{
    public ?string $width = '3';

    protected string|\Closure|array $permissions = '*';

    public function __construct(
        protected readonly UserRepository $users,
        protected readonly ContractRepository $contracts
    ) {
    }

    public function render(): View
    {
        $user = auth()->user();
        Log::info('User accessing ContractStatusWidget', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role->name ?? 'No role assigned'
        ]);

        $contractSignature = $user->contractSignature;

        $userStatus = $contractSignature ? $contractSignature->status : 'not_signed';

        $activeCount = $this->contracts->countByStatus('active');
        $expiredCount = $this->contracts->countByStatus('expired');

        Log::info('ContractStatusWidget data', [
            'userStatus' => $userStatus,
            'activeCount' => $activeCount,
            'expiredCount' => $expiredCount,
        ]);

        return view('plugins.dashboard.widgets.contract-status', [
            'userStatus' => $userStatus,
            'activeCount' => $activeCount,
            'expiredCount' => $expiredCount,
        ]);
    }

    public function scripts(): ?View
    {
        return null;
    }
}