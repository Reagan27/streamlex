<?php

namespace Vanguard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Vanguard\DocumentAcknowledgementAssignment;

class DocumentAcknowledgementAssigned extends Notification
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
            ->subject(__('New document acknowledgement assigned'))
            ->line(__('A new document acknowledgement has been assigned to you: :title', ['title' => $this->assignment->document->title]))
            ->action(__('Review document'), route('document_acknowledgements.assignments.show', $this->assignment))
            ->line(__('Please review and acknowledge the document to complete the signature process.'));
    }

    public function toArray($notifiable)
    {
        return [
            'document_acknowledgement_id' => $this->assignment->document->id,
            'assignment_id' => $this->assignment->id,
            'title' => $this->assignment->document->title,
        ];
    }
}
