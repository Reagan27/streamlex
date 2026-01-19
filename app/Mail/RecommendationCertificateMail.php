<?php

namespace Vanguard\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Vanguard\User;

class RecommendationCertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $type;
    public $overallRating;
    public $overallPercentage;
    public $pdfContent;

    public function __construct(User $user, $type, $overallRating, $overallPercentage, $pdfContent)
    {
        $this->user = $user;
        $this->type = $type;
        $this->overallRating = $overallRating;
        $this->overallPercentage = $overallPercentage;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->markdown('mail.recommendation-certificate')
                    ->subject(ucfirst($this->type))
                    ->attachData($this->pdfContent, $this->type . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}