<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ActivityStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $activity;
    protected $status;
    protected $user;

    public function __construct($activity, $status, $user)
    {
        $this->activity = $activity;
        $this->status = $status;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Field Activity Status Changed')
            ->greeting('Hello!')
            ->line('The status of a field activity has changed.')
            ->line('Activity: ' . $this->activity->title)
            ->line('New Status: ' . $this->status)
            ->line('Changed by: ' . $this->user->name)
            ->action('View Activity', url('/field-activities/' . $this->activity->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'activity_id' => $this->activity->id,
            'activity_title' => $this->activity->title,
            'status' => $this->status,
            'changed_by' => $this->user->id,
        ];
    }
}
