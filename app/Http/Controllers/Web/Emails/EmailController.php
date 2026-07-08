<?php

namespace Vanguard\Http\Controllers\Web\Emails;

use Vanguard\Email;
use Vanguard\ImportedEmail;
use Vanguard\EmailBatch;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Vanguard\User;
use Vanguard\County;
use Vanguard\Role;
use Vanguard\Group;
use Vanguard\Jobs\SendEmailJob;
use Illuminate\Support\Facades\DB;
use Vanguard\Projects;

class EmailController extends Controller
{
    /**
     * Display the main Emails page with sent emails and action buttons.
     */
    public function index(Request $request)
    {
        $query = EmailBatch::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('batchId', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });

            // Also allow searching within emails belonging to the batch (subject or body/message)
            $query->orWhereHas('emails', function ($q2) use ($search) {
                // Use full-text MATCH...AGAINST when running on MySQL/MariaDB for performance,
                // but fallback to LIKE for searches containing '@' or unsupported boolean characters.
                if (DB::connection()->getDriverName() === 'mysql' && ! $this->containsBooleanModeUnsafeCharacters($search)) {
                    $q2->whereRaw("MATCH(subject,message) AGAINST(? IN BOOLEAN MODE)", [$this->prepareBooleanModeSearchTerm($search)]);
                } else {
                    $q2->where('subject', 'like', "%{$search}%")
                       ->orWhere('message', 'like', "%{$search}%");
                }
            });
        }

        $emailBatches = $query->orderBy('created_at', 'desc')->paginate(10);
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();

        return view('emails.index', compact('emailBatches', 'groups'));
    }

    /**
     * Prepare a search term for MySQL boolean full-text search.
     */
    protected function prepareBooleanModeSearchTerm(string $term): string
    {
        // Escape MySQL full-text special characters and wrap phrases when necessary.
        $escaped = preg_replace('/[+\-<>~*"@\(\)\|\&\^\!\:\\]/', ' ', $term);
        $terms = array_filter(explode(' ', $escaped), fn($segment) => trim($segment) !== '');

        return implode(' ', array_map(function ($segment) {
            return '+' . $segment;
        }, $terms));
    }

    protected function containsBooleanModeUnsafeCharacters(string $term): bool
    {
        return (bool) preg_match('/@|"|<|>|~|\(|\)|\||\&|\^|!|:|-|\\\\/', $term);
    }

    /**
     * Display the form to send bulk emails.
     */
    public function showSendBulkEmail(Request $request)
    {
        $query = User::where('status', 'Active');

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('county_id')) {
            $query->where('county_id', $request->county_id);
        }

        if ($request->filled('project_id')) {
            $projectId = $request->project_id;
            $query->whereHas('projects', function ($q) use ($projectId) {
                $q->where('projects.id', $projectId)
                    ->where('projects_user.is_active_project', true);
            });
        }

        $users = $query->get();
        $roles = Role::where('name', '!=', 'Admin')->pluck('display_name', 'id');
        $counties = County::pluck('name', 'id');
        $projects = Projects::orderBy('name')->pluck('name', 'id');
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();

        return view('emails.send_bulk_email', compact('users', 'roles', 'counties', 'projects', 'groups'));
    }

    /**
     * Display the form to send single email.
     */
    public function showSendSingleEmail()
    {
        return view('emails.send_single_email');
    }

    /**
     * Display the form to send select emails.
     */
    public function showSendSelectEmail(Request $request)
    {
        $query = User::where('status', 'Active');

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('county_id')) {
            $query->where('county_id', $request->county_id);
        }

        if ($request->filled('project_id')) {
            $projectId = $request->project_id;
            $query->whereHas('projects', function ($q) use ($projectId) {
                $q->where('projects.id', $projectId)
                    ->where('projects_user.is_active_project', true);
            });
        }

        $users = $query->get();
        $roles = Role::where('name', '!=', 'Admin')->pluck('display_name', 'id');
        $counties = County::pluck('name', 'id');
        $projects = Projects::orderBy('name')->pluck('name', 'id');
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();

        return view('emails.send_select_email', compact('users', 'roles', 'counties', 'projects', 'groups'));
    }

    /**
     * Filter users for email sending.
     */
    public function filterUsers(Request $request)
    {
        $query = User::where('status', 'Active');

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('county_id')) {
            $query->where('county_id', $request->county_id);
        }

        if ($request->filled('project_id')) {
            $projectId = $request->project_id;
            $query->whereHas('projects', function ($q) use ($projectId) {
                $q->where('projects.id', $projectId)
                    ->where('projects_user.is_active_project', true);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->select('id', 'first_name', 'last_name', 'email')->get();

        return response()->json($users);
    }

    /**
     * Handle sending bulk emails.
     *
     * FIX: Previously, when using all_users or role+county filters, $recipients was a
     * collection of User *objects*. The loop then passed the whole object as the email
     * address, causing the RFC 2822 error. Now we always resolve to a plain email string
     * before creating the Email record or dispatching the job.
     */
    public function sendBulkEmail(Request $request)
    {

        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
            // Support multiple attachments named "attachments[]" or single "attachment"
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB each
            'attachment' => 'nullable|file|max:10240',
        ]);

        // Collect stored attachment paths and original names
        $attachmentPaths = [];
        $attachmentOriginalNames = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('attachments', 'public');
                $attachmentPaths[] = $p;
                $attachmentOriginalNames[] = $file->getClientOriginalName();
            }
        } elseif ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $p = $file->store('attachments', 'public');
            $attachmentPaths[] = $p;
            $attachmentOriginalNames[] = $file->getClientOriginalName();
        }

        $recipients = collect();
        $batchId = uniqid('bulk_email_');

        if ($request->input('all_users') == 1) {
            $query = User::where('status', 'Active');

            if ($request->filled('project_id')) {
                $query->whereHas('projects', function ($q) use ($request) {
                    $q->where('projects.id', $request->project_id)
                        ->where('projects_user.is_active_project', true);
                });
            }

            $recipients = $query->pluck('email');
        } elseif ($request->filled('group')) {
            $groupInput = is_array($request->group) ? $request->group : [$request->group];

            // Already returns plain email strings
            $recipients = ImportedEmail::whereIn('group_id', $groupInput)->pluck('email');
        } elseif ($request->filled('role') || $request->filled('county')) {
            $query = User::where('status', 'Active');

            if ($request->filled('role')) {
                $query->where('role_id', $request->role);
            }

            if ($request->filled('county')) {
                $query->where('county_id', $request->county);
            }

            if ($request->filled('project_id')) {
                $query->whereHas('projects', function ($q) use ($request) {
                    $q->where('projects.id', $request->project_id)
                        ->where('projects_user.is_active_project', true);
                });
            }

            $recipients = $query->pluck('email');
        }

        if ($recipients->isEmpty()) {
            return redirect()->back()->with('error', __('No recipients found.'));
        }

        $emailBatch = EmailBatch::create([
            'batchId'     => $batchId,
            'email_count' => $recipients->count(),
            'status'      => 'pending',
            'category'    => 'bulk',
            'user_id'     => auth()->user()->id,
        ]);

        foreach ($recipients as $recipientEmail) {
            // $recipientEmail is now always a plain string
            Log::info('Sending email to', ['email' => $recipientEmail]);

            Email::create([
                'recipient' => $recipientEmail,
                'subject'   => $request->subject,
                'message'   => $request->message,
                'batch_id'  => $batchId,
                'status'    => 'pending',
                // store JSON array of attachment paths (or null)
                'attachment' => count($attachmentPaths) ? json_encode($attachmentPaths) : null,
            ]);

            SendEmailJob::dispatch($recipientEmail, $request->subject, $request->message, $attachmentPaths, $attachmentOriginalNames);
        }

        return redirect()->back()->with('success', 'Bulk email queued for sending! Batch ID: ' . $batchId);
    }

    /**
     * Handle sending selected emails.
     */
    public function sendSelectEmail(Request $request)
    {
        $request->validate([
            'message'            => 'required|string',
            'subject'            => 'required|string',
            'selectedRecipients' => 'required|array',
            'attachments'        => 'nullable|array',
            'attachments.*'      => 'file|max:10240',
            'attachment'         => 'nullable|file|max:10240', // legacy single
        ]);

        $batchId = uniqid('select_email_');

        $attachmentPaths = [];
        $attachmentOriginalNames = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('attachments', 'public');
                $attachmentPaths[] = $p;
                $attachmentOriginalNames[] = $file->getClientOriginalName();
            }
        } elseif ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $p = $file->store('attachments', 'public');
            $attachmentPaths[] = $p;
            $attachmentOriginalNames[] = $file->getClientOriginalName();
        }

        $emailBatch = EmailBatch::create([
            'batchId'     => $batchId,
            'email_count' => count($request->selectedRecipients),
            'status'      => 'pending',
            'category'    => 'select',
            'user_id'     => auth()->user()->id,
        ]);

        $userIds = $request->selectedRecipients;

        foreach ($userIds as $id) {
            $user = User::find($id);

            if ($user) {
                Email::create([
                    'recipient' => $user->email,
                    'subject'   => $request->subject,
                    'message'   => $request->message,
                    'batch_id'  => $batchId,
                    'status'    => 'pending',
                    'attachment' => count($attachmentPaths) ? json_encode($attachmentPaths) : null,
                ]);
                    SendEmailJob::dispatch($user->email, $request->subject, $request->message, $attachmentPaths, $attachmentOriginalNames);
            } else {
                Log::warning("User not found for ID: {$id}");
            }
        }

        return redirect()->back()->with('success', count($userIds) . ' Emails queued');
    }

    /**
     * Handle sending a single email.
     *
     * FIX: Previously dispatched the whole $email Model object to SendEmailJob.
     * Now passes the plain string values directly, consistent with other methods.
     */
    public function sendSingleEmail(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject'   => 'required|string',
            'message'   => 'required|string',
        ]);

        $batchId = uniqid('single_email_');

        // Support attachments for single email too
        $request->validate([
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $attachmentPaths = [];
        $attachmentOriginalNames = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('attachments', 'public');
                $attachmentPaths[] = $p;
                $attachmentOriginalNames[] = $file->getClientOriginalName();
            }
        } elseif ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $p = $file->store('attachments', 'public');
            $attachmentPaths[] = $p;
            $attachmentOriginalNames[] = $file->getClientOriginalName();
        }

        Email::create([
            'recipient' => $request->input('recipient'),
            'subject'   => $request->input('subject'),
            'message'   => $request->input('message'),
            'batch_id'  => $batchId,
            'status'    => 'pending',
            'attachment' => count($attachmentPaths) ? json_encode($attachmentPaths) : null,
        ]);

        SendEmailJob::dispatch(
            $request->input('recipient'),
            $request->input('subject'),
            $request->input('message'),
            $attachmentPaths,
            $attachmentOriginalNames
        );

        return redirect()->back()->with('success', 'Email has been queued for sending.');
    }

    /**
     * View the details of an email batch.
     */
    public function viewBatch($batchNumber)
    {
        $query = Email::where('batch_id', $batchNumber);
        if (request()->filled('search')) {
            $s = request()->get('search');
            if (DB::connection()->getDriverName() === 'mysql' && ! $this->containsBooleanModeUnsafeCharacters($s)) {
                $query->whereRaw("MATCH(subject,message,recipient) AGAINST(? IN BOOLEAN MODE)", [$this->prepareBooleanModeSearchTerm($s)]);
            } else {
                $query->where(function ($q) use ($s) {
                    $q->where('subject', 'like', "%{$s}%")
                      ->orWhere('message', 'like', "%{$s}%")
                      ->orWhere('recipient', 'like', "%{$s}%");
                });
            }
        }
        $emails = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->only('search'));
        return view('emails.view_batch', compact('emails'));
    }

    /**
     * View the details of a single email.
     */
    public function viewSingle($id)
    {
        $email = Email::findOrFail($id);
        return view('emails.view', compact('email'));
    }

    /**
     * Download email as HTML file.
     */
    public function downloadEmail($id)
    {
        $email = Email::findOrFail($id);
        
        $htmlContent = $this->generateEmailHtml($email);
        
        $filename = 'email_' . $email->id . '_' . date('Y-m-d_His') . '.html';
        
        return response()->streamDownload(function () use ($htmlContent) {
            echo $htmlContent;
        }, $filename, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Generate HTML content for email download.
     */
    protected function generateEmailHtml($email)
    {
        $recipientName = $email->recipient ?? 'Recipient';
        $subject = htmlspecialchars($email->subject ?? 'No Subject');
        $message = $email->message ?? '';
        $sentAt = $email->formatted_created_at ?? 'N/A';
        $status = $email->status_description ?? 'N/A';
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$subject}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 25px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            word-break: break-word;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.95;
            margin: 5px 0;
        }
        
        .meta-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .meta-item {
            padding: 15px 25px;
            border-right: 1px solid #e0e0e0;
        }
        
        .meta-item:nth-child(2n) {
            border-right: none;
        }
        
        .meta-label {
            font-weight: 600;
            color: #667eea;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .meta-value {
            font-size: 14px;
            color: #555;
            word-break: break-all;
        }
        
        .email-body-wrapper {
            padding: 30px 25px;
        }
        
        .email-body {
            font-size: 15px;
            line-height: 1.8;
            color: #333;
        }
        
        .email-body p {
            margin-bottom: 1.2rem;
        }
        
        .email-body h1,
        .email-body h2,
        .email-body h3,
        .email-body h4,
        .email-body h5,
        .email-body h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #222;
        }
        
        .email-body h1 { font-size: 28px; }
        .email-body h2 { font-size: 24px; }
        .email-body h3 { font-size: 20px; }
        .email-body h4 { font-size: 18px; }
        
        .email-body ul,
        .email-body ol {
            margin-bottom: 1.2rem;
            padding-left: 2rem;
        }
        
        .email-body li {
            margin-bottom: 0.5rem;
        }
        
        .email-body a {
            color: #667eea;
            text-decoration: none;
        }
        
        .email-body a:hover {
            text-decoration: underline;
        }
        
        .email-body img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 1rem 0;
            border-radius: 4px;
        }
        
        .email-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.2rem 0;
        }
        
        .email-body table th,
        .email-body table td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: left;
        }
        
        .email-body table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .email-body blockquote {
            border-left: 4px solid #667eea;
            padding: 0.5rem 0 0.5rem 1rem;
            margin: 1.2rem 0;
            background-color: #f8f9fa;
            font-style: italic;
            color: #666;
        }
        
        .email-body code {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 0.2rem 0.4rem;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            color: #d63384;
        }
        
        .email-body pre {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 1rem;
            overflow-x: auto;
            margin: 1.2rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        
        .email-body pre code {
            background-color: transparent;
            border: none;
            padding: 0;
            color: inherit;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px 25px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
        
        .download-date {
            color: #888;
            font-size: 12px;
            margin-top: 10px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border-radius: 0;
            }
            .meta-info {
                page-break-inside: avoid;
            }
        }
        
        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }
            .meta-info {
                grid-template-columns: 1fr;
            }
            .meta-item {
                border-right: none !important;
                border-bottom: 1px solid #e0e0e0;
            }
            .meta-item:last-child {
                border-bottom: none;
            }
            .header {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
            .email-body-wrapper {
                padding: 20px 15px;
            }
            .email-body {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{$subject}</h1>
            <p>Email ID: #{$email->id}</p>
            <p>To: {$recipientName}</p>
        </div>
        
        <div class="meta-info">
            <div class="meta-item">
                <div class="meta-label">Recipient</div>
                <div class="meta-value">{$recipientName}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Status</div>
                <div class="meta-value">{$status}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Sent Date</div>
                <div class="meta-value">{$sentAt}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Email ID</div>
                <div class="meta-value">#{$email->id}</div>
            </div>
        </div>
        
        <div class="email-body-wrapper">
            <div class="email-body">
                {$message}
            </div>
        </div>
        
        <div class="footer">
            <p>This email was downloaded on <strong>{date('Y-m-d H:i:s')}</strong></p>
            <p class="download-date">Email Management System</p>
        </div>
    </div>
</body>
</html>
HTML;
        
        return $html;
    }

    /**
     * Delete an email.
     */
    public function destroy(Email $email)
    {
        $email->delete();
        return redirect()->back()->with('success', 'Email deleted successfully!');
    }
}