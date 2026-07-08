<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class AfricasTalkingSmsChannel
{
    /**
     * Returns true only when the AfricasTalking SDK is available.
     */
    public static function isAvailable(): bool
    {
        return class_exists('AfricasTalking\SDK\AfricasTalking');
    }

    public function send($notifiable, Notification $notification)
    {
        if (!self::isAvailable()) {
            return;
        }

        if (!method_exists($notification, 'toAfricasTalkingSms')) {
            return;
        }

        $message = $notification->toAfricasTalkingSms($notifiable);
        $phone   = $notifiable->phone ?? null;

        if (!$phone || !$message) {
            return;
        }

        $username = config('services.africastalking.username');
        $apiKey   = config('services.africastalking.key');

        $AT  = new \AfricasTalking\SDK\AfricasTalking($username, $apiKey);
        $sms = $AT->sms();
        $sms->send([
            'to'      => [$phone],
            'message' => $message,
        ]);
    }
}
