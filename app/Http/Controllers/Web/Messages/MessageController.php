<?php

namespace Vanguard\Http\Controllers\Web\Messages;

use Carbon\Carbon;
use Vanguard\Role;
use Vanguard\User;
use Vanguard\Group;
use Vanguard\County;
use Vanguard\Contact;
use Vanguard\Message;
use Vanguard\MessageBatch;
use Illuminate\Http\Request;
use Vanguard\Jobs\SendMessageJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Vanguard\Http\Controllers\Controller;

class MessageController extends Controller
{
    /**
     * Display the main Messages page with sent messages and action buttons.
     */
    public function index()
    {
        $messageBatches = MessageBatch::orderBy('created_at', 'desc')->paginate(10);
        $groups = Group::all();
        return view('messages.index', compact('messageBatches', 'groups'));
    }

    // public function userMessages()
    // {
    //     $user = auth()->user();
    //     $messages = Message::where(function ($query) use ($user) {
    //         $query->where('recipient', $user->phone)
    //               ->orWhere('user_id', $user->id);
    //     })
    //     ->latest()
    //     ->paginate(15);

    //     return view('messages.user_messages', compact('messages'));
    // }

    /**
     * Display the form to send bulk SMS.
     */
    public function showSendBulkSms(Request $request)
    {
        $query = User::where('status', 'Active');

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('county_id')) {
            $query->where('county_id', $request->county_id);
        }

        $users = $query->get();
       
        $roles = Role::all();

        $counties = County::pluck('name', 'id');

        // $messages = Message::nonTemplates()->get();

        $groups = Group::where('type', Group::TYPE_CONTACT)->get();

        return view('messages.send_bulk_sms', compact('users', 'roles', 'counties', 'groups'));
    }
    
    /**
     * Display the form to send single SMS.
     */
    public function showSendSingleSms()
    {
        return view('messages.send_single_sms');
    }

    /**
     * Display the form to send select SMS.
     */
    public function showSendSelectSms(Request $request)
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

        $groups = Group::where('type', Group::TYPE_CONTACT)->get();
        
        return view('messages.send_select_sms', compact('users', 'roles', 'counties', 'groups'));
    }
    

    public function filterUsers(Request $request)
    {
        $users = collect();
    
        if ($request->filled('group_id')) {
            $groupId = $request->input('group_id');
            $users = Contact::where('group_id', $groupId)
                            ->select('id', 'name', 'phone')
                            ->get();
        } else {
            $query = User::where('status', 'Active');
            
            if ($request->filled('role_id')) {
                $query->where('role_id', $request->role_id);
            }
        
            if ($request->filled('county_id')) {
                $query->where('county_id', $request->county_id);
            }
        
            $users = $query->select('id', 'first_name as name', 'phone')->get();
        }
        
        return response()->json($users);
    }
          

    /**
     * Display the message templates.
     */
    public function showMessageTemplates()
    {
        $templates = Message::where('is_template', true)->get();
        return view('messages.message_templates', compact('templates'));
    }

    /**
     * Convert a local phone number to international format.
     *
     * @param string $phone
     * @return string
     */
    function formatPhoneNumber($phone)
    {
        if (preg_match('/^0[17]/', $phone)) {
            $phone = '+254' . substr($phone, 1);
        }

        return $phone;
    }


    /**
     * Handle sending bulk SMS.
     */
public function sendBulkSms(Request $request)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $all = $request->all();
    $all['batchId'] = uniqid('bulk_');

    // Retrieve recipients based on filters
    if ($request->input('all_users') == 1) {
        $recipients = User::where('status', 'Active')->get();
        $all['category'] = 'bulk';
    } elseif ($request->filled('group')) {
        $recipients = Contact::where('group_id', $request->group)->get();
        $all['category'] = 'Group';
        
    } elseif ($request->filled('role') && $request->filled('county')) {
        $recipients = User::where('role_id', $request->input('role'))
                          ->where('status', 'Active')
                          ->where('county_id', $request->input('county'))
                          ->get();
        $all['category'] = 'bulk';
    } elseif ($request->filled('county')) {
        $recipients = User::where('status', 'Active')
                          ->where('county_id', $request->input('county'))
                          ->get();
        $all['category'] = 'bulk';
    } elseif ($request->filled('role')) {
        $recipients = User::where('role_id', $request->input('role'))
                          ->where('status', 'Active')
                          ->get();
        $all['category'] = 'bulk';
    } else {
        return redirect()->back()->with('error', __('Please select All Users, a Group, or a Role to send the message.'));
    }

    if ($recipients->isEmpty()) {
        return redirect()->back()->with('error', __('No recipients found.'));
    }

    $all['count'] = $recipients->count();

    // Create a message batch record
    $batch = new MessageBatch();
    $batch->date_time = now();
    $batch->batchId = $all['batchId'];
    $batch->category = $all['category'];
    $batch->count = $all['count'];
    $batch->user_id = auth()->user()->id;
    $batch->save();

    // Dispatch job with all recipients and message details
    SendMessageJob::dispatch($all, $recipients);

    return redirect()->back()->with('success', 'Bulk SMS queued for sending! Batch ID: ' . $all['batchId']);
}

    public function sendSelectSms(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'selectedRecipients' => 'required|array',
        ]);
    
        $batchId = uniqid('select_');
        $all = $request->all();
        $all['batchId'] = $batchId;
        $all['category'] = 'select';
        $all['recipients'] = [];
    
        $userIds = $request->selectedRecipients;
        $all['count'] = count($userIds);
    
        $batch = new MessageBatch();
        $batch->date_time = Carbon::now()->format('Y-m-d H:i:s');
        $batch->batchId = $all['batchId'];
        $batch->category = $all['category'];
        $batch->count = $all['count'];
        $batch->user_id = auth()->user()->id;
        $batch->save();
    
        foreach ($userIds as $id) {
            $user = User::find($id);
    
            if ($user) {
                $all['phone'] = $user->phone;
                $all['email'] = $user->email;
                $all['user_id'] = $user->id;
    
                $all['recipients'][] = [
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'id' => $user->id,
                ];
    
                SendMessageJob::dispatch($all);
            } else {
                Log::warning("User not found for ID: {$id}");
            }
        }
    
        return redirect()->back()->with('success', $all['count'] . ' Messages queued');
    }
    
                                      

    /**
     * Reusable function to send SMS to an array of recipients.
     */
    private function sendSmsToRecipients(array $recipients, string $message)
    {
        $username = config('services.africastalking.username');
        $apiKey = config('services.africastalking.api_key');
        $senderId = config('services.africastalking.sender_id');

        foreach ($recipients as $recipient) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'apiKey' => $apiKey,
            ])->post('https://api.africastalking.com/version1/messaging', [
                'username' => $username,
                'to' => $recipient,
                'from' => $senderId,
                'message' => $message,
            ]);

            $this->storeMessage($recipient, $message, $response->json());
        }
    }

    public function viewBatch($batchNumber)
    {
        $messages = Message::where('batch_id', $batchNumber)->paginate(10);
        return view('messages.view_batch', compact('messages'));
    }

    public function viewSingle($id)
    {
        $message = Message::findOrFail($id);
        return view('messages.view_single', compact('message'));
    }


    /**
     * Store message details in the database.
     */
    private function storeMessage($recipient, $messageText, $response)
    {
        $name = $recipient['name'];
        $contact = $recipient['contact'];
    
        Message::create([
            'name' => $name,
            'contact' => $contact,
            'message' => $messageText,
            'status' => $response['statusCode'] ?? Message::STATUS_PENDING,
            'message_id' => $response['messageId'] ?? null,
            'message_cost' => $response['cost'] ?? null,
            'batch_id' => $response['batch_id'] ?? null,
            'category' => $response['category'] ?? 'General',
            'date' => now(),
        ]);
    }    
}