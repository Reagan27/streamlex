<?php

namespace Vanguard\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Vanguard\User;

class RecommendationRejection extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $overallRating;
    public $overallPercentage;

    public function __construct(User $user, $overallRating, $overallPercentage)
    {
        $this->user = $user;
        $this->overallRating = $overallRating;
        $this->overallPercentage = $overallPercentage;
    }

    public function build()
    {
        return $this->markdown('mail.recommendation-rejection')
                    ->subject('Performance Review Feedback');
    }
}