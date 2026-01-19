<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class HelloWidget extends Widget
{
    public ?string $width = '4';

    protected string|\Closure|array $permissions = 'contract.status';

    public function __construct(protected readonly UserRepository $users)
    {
    }

    public function render(): View
    {
        $user = Auth::user();
        $contractSignature = $user->contractSignature;

        if ($user->onboarding_status) {
            return view('widgets.hello', compact('contractSignature', 'user'));
        } else {
            return view('widgets.onboarding_incomplete');
        }
    }
}