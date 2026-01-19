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

class EmailController extends Controller
{
    /**
     * Display the main Emails page with sent emails and action buttons.
     */
    public function index()
    {
        $emailBatches = EmailBatch::orderBy('created_at', 'desc')->paginate(10);
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();

        return view('emails.index', compact('emailBatches', 'groups'));
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

        $users = $query->get();
        $roles = Role::whereIn('name', ['Regional Coordinator', 'County Coordinator', 'Supervisor', 'Field Officer'])->pluck('name', 'id');
        $counties = County::pluck('name', 'id');
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();

        return view('emails.send_bulk_email', compact('users', 'roles', 'counties', 'groups'));
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

        $users = $query->get();
        $roles = Role::where('name', '!=', 'Admin')->pluck('name', 'id');
        $counties = County::pluck('name', 'id');
        $groups = Group::where('type', Group::TYPE_EMAIL)->get();

        return view('emails.send_select_email', compact('users', 'roles', 'counties', 'groups'));
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
    
        // if ($request->filled('group_id')) {
        //     $query->whereIn('id', function($subQuery) use ($request) {
        //         $subQuery->select('user_id')
        //                  ->from('imported_emails')
        //                  ->where('group_id', $request->group_id);
        //     });
        // }
    
        $users = $query->select('id', 'first_name', 'last_name', 'email')->get();
    
        return response()->json($users);
    }
    

    /**
     * Handle sending bulk emails.
     */
    public function sendBulkEmail(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);
    
        $recipients = collect();
        $batchId = uniqid('bulk_email_');
    
        if ($request->input('all_users') == 1) {
            $recipients = User::where('status', 'Active')->get();
        } 
        elseif ($request->filled('group')) {
            $groupInput = is_array($request->group) ? $request->group : [$request->group];
            
            $recipients = ImportedEmail::whereIn('group_id', $groupInput)->pluck('email');
        }
        elseif ($request->filled('role') && $request->filled('county')) {
            $recipients = User::where('role_id', $request->role)
                ->where('status', 'Active')
                ->where('county_id', $request->county)
                ->get();
        }
    
        if ($recipients->isEmpty()) {
            return redirect()->back()->with('error', __('No recipients found.'));
        }
    
        $emailBatch = EmailBatch::create([
            'batchId' => $batchId,
            'email_count' => $recipients->count(),
            'status' => 'pending',
            'category' => 'bulk',
            'user_id' => auth()->user()->id,
        ]);
    
        foreach ($recipients as $recipientEmail) {
            $email = Email::create([
                'recipient' => $recipientEmail,
                'subject' => $request->subject,
                'message' => $request->message,
                'batch_id' => $batchId,
                'status' => 'pending',
            ]);
    
            SendEmailJob::dispatch($recipientEmail, $request->subject, $request->message);
        }
    
        return redirect()->back()->with('success', 'Bulk email queued for sending! Batch ID: ' . $batchId);
    }
    

    // public function sendGroupEmail(Request $request)
    // {
    //     $request->validate([
    //         'message' => 'required|string',
    //         'subject' => 'required|string',
    //         'group_id' => 'required|integer|exists:groups,id',
    //     ]);

    //     $groupMembers = ImportedEmail::where('group_id', $request->group_id)->pluck('email');

    //     if ($groupMembers->isEmpty()) {
    //         return redirect()->back()->with('error', __('No members found in the selected group.'));
    //     }

    //     $batchId = uniqid('group_email_');

    //     $emailBatch = EmailBatch::create([
    //         'batchId' => $batchId,
    //         'email_count' => $groupMembers->count(),
    //         'status' => 'pending',
    //         'category' => 'group',
    //     ]);

    //     foreach ($groupMembers as $recipientEmail) {
    //         $email = Email::create([
    //             'recipient' => $recipientEmail,
    //             'subject' => $request->subject,
    //             'message' => $request->message,
    //             'batch_id' => $batchId,
    //             'status' => 'pending',
    //         ]);

    //         SendEmailJob::dispatch($email);
    //     }

    //     return redirect()->back()->with('success', 'Group email queued for sending! Batch ID: ' . $batchId);
    // }

    /**
     * Handle sending selected emails.
     */
    public function sendSelectEmail(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
            'selectedRecipients' => 'required|array',
        ]);
    
        $batchId = uniqid('select_email_');
        $all = $request->all();
        $all['batchId'] = $batchId;
        $all['category'] = 'select';
    
        $emailBatch = EmailBatch::create([
            'batchId' => $batchId,
            'email_count' => count($request->selectedRecipients),
            'status' => 'pending',
            'category' => 'select',
            'user_id' => auth()->user()->id,
        ]);
    
        $userIds = $request->selectedRecipients;
    
        foreach ($userIds as $id) {
            $user = User::find($id);
    
            if ($user) {
                $email = Email::create([
                    'recipient' => $user->email,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'batch_id' => $batchId,
                    'status' => 'pending',
                ]);
    
                SendEmailJob::dispatch($user->email, $request->subject, $request->message);
            } else {
                Log::warning("User not found for ID: {$id}");
            }
        }

        return redirect()->back()->with('success', count($userIds) . ' Emails queued');
    }
    
    /**
     * Handle sending a single email.
     */
    public function sendSingleEmail(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $batchId = uniqid('single_email_');

        $email = Email::create([
            'recipient' => $request->input('recipient'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'batch_id' => $batchId,
            'status' => 'pending',
        ]);

        SendEmailJob::dispatch($email);

        return redirect()->back()->with('success', 'Email has been queued for sending.');
    }

    /**
     * View the details of an email batch.
     */
    public function viewBatch($batchNumber)
    {
        $emails = Email::where('batch_id', $batchNumber)->paginate(10);
        return view('emails.view_batch', compact('emails'));
    }

    /**
     * View the details of a single email.
     */
    public function viewSingle($id)
    {
        $email = Email::findOrFail($id);
        return view('emails.view_single', compact('email'));
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
