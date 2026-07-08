<?php

namespace Vanguard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Vanguard\ComplianceDocument;

class ComplianceExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ComplianceDocument $document, protected string $stage)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Compliance document reminder')
            ->line('The compliance document "' . $this->document->name . '" requires attention.')
            ->line('Current reminder stage: ' . str_replace('_', ' ', $this->stage))
            ->action('Open compliance module', url('/compliance'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'document_name' => $this->document->name,
            'stage' => $this->stage,
            'message' => 'Compliance document "' . $this->document->name . '" needs attention.',
        ];
    }
}
