<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Vanguard\ComplianceDocument;
use Vanguard\Notifications\ComplianceExpiryNotification;
use Vanguard\User;

class SendComplianceReminders extends Command
{
    protected $signature = 'compliance:send-reminders';
    protected $description = 'Send reminder notifications for compliance documents nearing expiry';

    public function handle(): int
    {
        $recipients = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['Admin', 'Manager', 'Finance']);
        })->get();

        if ($recipients->isEmpty()) {
            return self::SUCCESS;
        }

        $documents = ComplianceDocument::whereNotNull('expiry_date')->get();

        foreach ($documents as $document) {
            $daysRemaining = $document->days_remaining;
            $stage = $document->reminderStageForDays($daysRemaining ?? 999);

            if ($stage === null && ($daysRemaining === null || $daysRemaining > 90)) {
                continue;
            }

            $shouldSend = false;
            if ($daysRemaining <= 4) {
                $shouldSend = !$document->last_reminder_at || $document->last_reminder_at->lt(now()->subDay());
            } else {
                $shouldSend = !$document->last_reminder_at || $document->last_reminder_stage !== $stage;
            }

            if ($shouldSend) {
                Notification::send($recipients, new ComplianceExpiryNotification($document, $stage));
                $document->update([
                    'last_reminder_at' => now(),
                    'last_reminder_stage' => $stage,
                ]);
            }
        }

        return self::SUCCESS;
    }
}
