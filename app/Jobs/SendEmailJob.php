<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Vanguard\Email;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient;
    protected $subject;
    protected $message;
    protected $attachmentPaths;
    protected $attachmentOriginalNames;

    public function __construct($recipient, $subject, $message, $attachmentPaths = null, $attachmentOriginalNames = null)
    {
        $this->recipient            = $recipient;
        $this->subject              = $subject;
        $this->message              = $message;
        $this->attachmentPaths      = is_array($attachmentPaths) ? $attachmentPaths : ($attachmentPaths ? [$attachmentPaths] : []);
        $this->attachmentOriginalNames = is_array($attachmentOriginalNames) ? $attachmentOriginalNames : ($attachmentOriginalNames ? [$attachmentOriginalNames] : []);
    }

    public function handle()
    {
        Log::info("Sending email to {$this->recipient} with subject: {$this->subject}");

        // Capture $this properties for use inside closure
        $recipient            = $this->recipient;
        $subject              = $this->subject;
        $message              = $this->message;
        $attachmentPaths       = $this->attachmentPaths;
        $attachmentOriginalNames = $this->attachmentOriginalNames;

        try {
            Mail::html($message, function ($mail) use ($recipient, $subject, $attachmentPaths, $attachmentOriginalNames) {
                $mail->to($recipient)
                     ->subject($subject)
                     ->from(config('mail.from.address'), config('mail.from.name'));
                if (!empty($attachmentPaths)) {
                    foreach ($attachmentPaths as $i => $p) {
                        $orig = $attachmentOriginalNames[$i] ?? basename($p);
                        try {
                            $mail->attach(public_path('storage/' . $p), ['as' => $orig]);
                        } catch (\Exception $e) {
                            Log::warning("Failed to attach file {$p} to email to {$recipient}: " . $e->getMessage());
                        }
                    }
                }
            });

            // Update existing email record status (already created in controller)
            Email::where('recipient', $recipient)
                 ->where('status', 'pending')
                 ->latest()
                 ->first()
                 ?->update(['status' => Email::STATUS_SENT, 'sent_at' => now()]);

            Log::info("Email sent successfully to {$recipient}");

        } catch (\Exception $e) {
            Email::where('recipient', $recipient)
                 ->where('status', 'pending')
                 ->latest()
                 ->first()
                 ?->update(['status' => Email::STATUS_FAILED, 'response' => $e->getMessage()]);

            Log::error("Failed to send email to {$recipient}: " . $e->getMessage());
        }
    }
}