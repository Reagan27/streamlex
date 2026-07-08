<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\FieldActivity;

class ActivityReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $activity;

    public function __construct(FieldActivity $activity)
    {
        $this->activity = $activity;
    }

    public function via($notifiable)
    {
        $channels = ['mail'];
        if ($notifiable->phone && \App\Notifications\Channels\AfricasTalkingSmsChannel::isAvailable()) {
            $channels[] = \App\Notifications\Channels\AfricasTalkingSmsChannel::class;
        }
        return $channels;
    }
    public function toAfricasTalkingSms($notifiable)
    {
        return 'REMINDER: ' . $this->activity->name . ' (' . $this->activity->start_date . ' - ' . $this->activity->end_date . '). Check your dashboard for details.';
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Field Activity Reminder: ' . $this->activity->name)
            ->greeting('Hello!')
            ->line('This is a reminder for the field activity: ' . $this->activity->name)
            ->line('Description: ' . $this->activity->description)
            ->line('Start Date: ' . $this->activity->start_date)
            ->line('End Date: ' . $this->activity->end_date)
            ->line('Location: ' . $this->activity->location)
            ->line('Deadline: ' . ($this->activity->deadline ?: 'N/A'))
            ->action('View Activity', url(route('field-activities.show', $this->activity->id)))
            ->line('Thank you.');
    }
}
