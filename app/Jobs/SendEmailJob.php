<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient;
    protected $subject;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $message
     */
    public function __construct($recipient, $subject, $message)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Sending email to {$this->recipient} with subject {$this->subject}");

        try {
            Mail::raw($this->message, function ($mail) {
                $mail->to($this->recipient)
                    ->subject($this->subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info("Email sent successfully to {$this->recipient}");
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$this->recipient}: {$e->getMessage()}");
        }
    }
}
