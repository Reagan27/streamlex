<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\GeneralReport;
use Vanguard\BackToOfficeReport;
use Vanguard\ReportReminderLog;
use Vanguard\User;
use App\Notifications\ReportSubmissionReminder;

class SendReportReminders extends Command
{
    protected $signature = 'reports:send-reminders';
    protected $description = 'Send reminder notifications for overdue report submissions';

    public function handle(): int
    {
        $users = User::whereHas('role')->get();

        foreach ($users as $user) {
            $this->sendReminder($user, 'general');
            $this->sendReminder($user, 'back_to_office');
        }

        return self::SUCCESS;
    }

    protected function sendReminder(User $user, string $reportType): void
    {
        $hasSubmitted = $this->hasSubmittedReport($user, $reportType);

        if ($hasSubmitted) {
            return;
        }

        $lastSent = ReportReminderLog::where('user_id', $user->id)
            ->where('report_type', $reportType)
            ->latest('sent_at')
            ->first();

        if ($lastSent && $lastSent->sent_at->diffInDays(now()) < 3) {
            return;
        }

        $label = $reportType === 'general' ? 'General' : 'Back to Office';
        $message = "You have not yet submitted your {$label} report for this month. Please submit it as soon as possible.";

        $user->notify(new ReportSubmissionReminder($reportType, $message));

        ReportReminderLog::create([
            'user_id' => $user->id,
            'report_type' => $reportType,
            'sent_at' => now(),
        ]);
    }

    protected function hasSubmittedReport(User $user, string $reportType): bool
    {
        $query = $reportType === 'general'
            ? GeneralReport::where('created_by', $user->id)
            : BackToOfficeReport::where('created_by', $user->id);

        return $query->whereIn('status', ['submitted', 'approved'])
            ->where('created_at', '>=', now()->subDays(30))
            ->exists();
    }
}
