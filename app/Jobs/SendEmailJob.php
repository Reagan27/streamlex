<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Vanguard\Email; // <-- Make sure this is imported

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient;
    protected $subject;
    protected $message;

    public function __construct($recipient, $subject, $message)
    {
        $this->recipient = $recipient;
        $this->subject   = $subject;
        $this->message   = $message;
    }

    public function handle()
    {
        Log::info("Sending email to {$this->recipient} with subject: {$this->subject}");

        // 1. Save to your emails table first (for tracking)
        $emailRecord = Email::create([
            'recipient' => $this->recipient,
            'subject'   => $this->subject,
            'message'   => $this->message,
            'status'    => Email::STATUS_PENDING,
            'category'  => 'welcome', // or pass from controller if you want
            'user_id'   => null,      // optional: set when you have user
        ]);

        try {
            // THIS IS THE KEY FIX → use Mail::html() instead of Mail::raw()
            Mail::html($this->message, function ($mail) {
                $mail->to($this->recipient)
                     ->subject($this->subject)
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });

            // Success → update status
            $emailRecord->update([
                'status'   => Email::STATUS_SENT,
                'sent_at'  => now(),
            ]);

            Log::info("Email sent successfully to {$this->recipient}");

        } catch (\Exception $e) {
            // Failed → update status
            $emailRecord->update([
                'status'   => Email::STATUS_FAILED,
                'response' => $e->getMessage(),
            ]);

            Log::error("Failed to send email to {$this->recipient}: " . $e->getMessage());
        }
    }
}
