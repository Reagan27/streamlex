<?php

namespace Vanguard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Vanguard\DocumentAcknowledgementAssignment;

class DocumentAcknowledgementSigned extends Notification
{
    use Queueable;

    private DocumentAcknowledgementAssignment $assignment;

    public function __construct(DocumentAcknowledgementAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Document acknowledgement completed'))
            ->line(__('A document acknowledgement has been signed by :user.', ['user' => $this->assignment->user->name]))
            ->action(__('View document'), route('compliance.document_acknowledgements.show', $this->assignment->document))
            ->line(__('You can download the signed document from the admin area.'));
    }

    public function toArray($notifiable)
    {
        return [
            'document_acknowledgement_id' => $this->assignment->document->id,
            'assignment_id' => $this->assignment->id,
            'signed_by' => $this->assignment->user->name,
            'title' => $this->assignment->document->title,
        ];
    }
}
