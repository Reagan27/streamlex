<?php

namespace Vanguard\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Vanguard\User;

class ContractDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $declineReason;

    public function __construct(User $user, $declineReason)
    {
        $this->user = $user;
        $this->declineReason = $declineReason;
    }

    public function build()
    {
        return $this->markdown('mail.contract-declined')
                    ->subject('Your Contract Has Been Declined');
    }
}