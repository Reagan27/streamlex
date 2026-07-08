<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage; 
use Illuminate\Notifications\Notification;

class ReportSubmissionReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $reportType, private readonly string $message)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Report Submission Reminder',
            'message' => $this->message,
            'report_type' => $this->reportType,
            'icon' => 'fas fa-exclamation-circle',
        ];
    }
}
